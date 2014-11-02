<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 7/6/14
 * Time: 10:58 AM
 */

namespace Tzookb\TBMsg\Repositories;


use Tzookb\TBMsg\Exceptions\ConversationNotFoundException;
use Tzookb\TBMsg\Exceptions\NotEnoughUsersInConvException;
use Tzookb\TBMsg\Exceptions\UserNotInConvException;
use Tzookb\TBMsg\Models\Eloquent\ConversationUsers;
use Tzookb\TBMsg\Models\Eloquent\MessageStatus;
use Tzookb\TBMsg\Repositories\Contracts\iTBMsgRepository;
use DB;
use Tzookb\TBMsg\Models\Eloquent\Message as MessageEloquent;
use Tzookb\TBMsg\Models\Eloquent\Conversation as ConversationEloquent;

class EloquentTBMsgRepository implements iTBMsgRepository
{
    protected $usersTable;
    protected $usersTableKey;
    protected $tablePrefix;

    public function __construct($tablePrefix, $usersTable, $usersTableKey) {
        $this->usersTable = $usersTable;
        $this->usersTableKey = $usersTableKey;
        $this->tablePrefix = $tablePrefix;
    }

    public function createConversation( $users_ids ) {
        if ( count($users_ids ) > 1 ) {
            //create new conv
            $conv = new ConversationEloquent();
            $conv->save();

            //get the id of conv, and add foreach user a line in conv_users
            foreach ( $users_ids as $user_id ) {
                $conv_user = new ConversationUsers();
                $conv_user->conv_id = $conv->id;
                $conv_user->user_id = $user_id;
                try{
                    $conv_user->save();
                } catch ( \Exception $ex ) {

                }
            }
            $eventData = [
                'usersIds' => $users_ids,
                'convId' => $conv->id
            ];
            //$this->dispatcher->fire('conversation.created',[$eventData]);
            return $conv;
        } else
            throw new NotEnoughUsersInConvException;
    }

    public function addMessageToConversation($conv_id, $user_id, $content) {
        //check if user of message is in conversation
        if ( !$this->isUserInConversation($conv_id, $user_id) )
            throw new UserNotInConvException;

        //if so add new message
        $message = new MessageEloquent();
        $message->sender_id = $user_id;
        $message->conv_id = $conv_id;
        $message->content = $content;
        $message->save();

        //get all users in conversation
        $usersInConv = $this->getUsersInConversation($conv_id);

        //and add msg status for each user in conversation
        foreach ( $usersInConv as $userInConv ) {
            $messageStatus = new MessageStatus();
            $messageStatus->user_id = $userInConv;
            $messageStatus->msg_id = $message->id;
            if ( $userInConv == $user_id ) {
                //its the sender user
                $messageStatus->self = 1;
                $messageStatus->status = self::READ;
            } else {
                //other users in conv
                $messageStatus->self = 0;
                $messageStatus->status = self::UNREAD;
            }
            $messageStatus->save();
        }

        return [
            'senderId' => $user_id,
            'convUsersIds' =>$usersInConv,
            'content' => $content,
            'convId' => $conv_id
        ];
    }

    public function getConversationByTwoUsers($userA_id, $userB_id) {
        $results = DB::select(
            '
            SELECT cu.conv_id
            FROM conv_users cu
            WHERE cu.user_id=? OR cu.user_id=?
            GROUP BY cu.conv_id
            HAVING COUNT(cu.conv_id)=2
            '
            , array($userA_id, $userB_id));
        if( count($results) == 1 ) {
            return (int)$results[0]->conv_id;
        }
        throw new ConversationNotFoundException;
    }

    public function markMessageAs($msgId, $userId, $status) {
        DB::statement(
            '
            UPDATE '.$this->tablePrefix.'messages_status mst
            SET mst.status=?
            WHERE mst.user_id=?
            AND mst.msg_id=?
            ',
            array($status, $userId, $msgId)
        );
    }

    public function markMessageAsRead($msgId, $userId) {
        $this->markMessageAs($msgId, $userId, self::READ);
    }
    public function markMessageAsUnread($msgId, $userId) {
        $this->markMessageAs($msgId, $userId, self::UNREAD);
    }
    public function markMessageAsDeleted($msgId, $userId) {
        $this->markMessageAs($msgId, $userId, self::DELETED);
    }
    public function markMessageAsArchived($msgId, $userId) {
        $this->markMessageAs($msgId, $userId, self::ARCHIVED);
    }

    public function isUserInConversation($conv_id, $user_id) {
        $results = DB::select(
            '
            SELECT COUNT(cu.conv_id)
            FROM '.$this->tablePrefix.'conv_users cu
            WHERE cu.user_id=?
            AND cu.conv_id=?
            HAVING COUNT(cu.conv_id)>0
            ',
            array($user_id, $conv_id)
        );
        if ( empty($results) )
            return false;
        return true;
    }

    public function getUsersInConversation($conv_id) {
        $results = DB::select(
            '
            SELECT cu.user_id
            FROM '.$this->tablePrefix.'conv_users cu
            WHERE cu.conv_id=?
            ',
            array($conv_id)
        );

        $usersInConvIds = array();
        foreach ( $results as $row ) {
            $usersInConvIds[] = $row->user_id;
        }
        return $usersInConvIds;
    }

    public function getNumOfUnreadMsgs($user_id) {
        $results = DB::select(
            '
            SELECT COUNT(mst.id) as numOfUnread
            FROM '.$this->tablePrefix.'messages_status mst
            WHERE mst.user_id=?
            AND mst.status=?
            ',
            array($user_id, self::UNREAD)
        );
        return (isset($results[0]))? $results[0]->numOfUnread : 0;
    }

    public function markReadAllMessagesInConversation($conv_id, $user_id) {
        DB::statement(
            '
            UPDATE '.$this->tablePrefix.'messages_status mst
            SET mst.status=?
            WHERE mst.user_id=?
            AND mst.status=?
            AND mst.msg_id IN (
              SELECT msg.id
              FROM messages msg
              WHERE msg.conv_id=?
              AND msg.sender_id!=?
            )
            ',
            array(self::READ, $user_id, self::UNREAD, $conv_id, $user_id)
        );
    }

    public function deleteConversation($conv_id, $user_id) {
        DB::statement(
            '
            UPDATE '.$this->tablePrefix.'messages_status mst
            SET mst.status='.self::DELETED.'
            WHERE mst.user_id=?
            AND mst.msg_id IN (
              SELECT msg.id
              FROM messages msg
              WHERE msg.conv_id=?
            )
            ',
            array($user_id, $conv_id)
        );
    }

    public function getConversationMessages($conv_id, $user_id, $newToOld = true)
    {
        if ( $newToOld )
            $orderBy = 'desc';
        else
            $orderBy = 'asc';

        return DB::select(
            '
            SELECT msg.id as msgId, msg.content, mst.status, msg.created_at, us.'.$this->usersTableKey.' as userId
            FROM '.$this->tablePrefix.'messages_status mst
            INNER JOIN '.$this->tablePrefix.'messages msg
            ON mst.msg_id=msg.id
            INNER JOIN '.$this->usersTable.' us
            ON msg.sender_id=us.'.$this->usersTableKey.'
            WHERE msg.conv_id=?
            AND mst.user_id = ?
            AND mst.status NOT IN (?,?)
            ORDER BY msg.created_at '.$orderBy.'
            '
            , array($conv_id, $user_id, self::DELETED, self::ARCHIVED));
    }

    public function getConversations($user_id)
    {
        return DB::select(
            '
            SELECT msg.conv_id as conv_id, msg.created_at, msg.id msgId, msg.content, mst.status, mst.self, us.'.$this->usersTableKey.' userId
            FROM '.$this->tablePrefix.'messages msg
            INNER JOIN (
                SELECT MAX(created_at) created_at
                FROM '.$this->tablePrefix.'messages
                GROUP BY conv_id
            ) m2 ON msg.created_at = m2.created_at
            INNER JOIN '.$this->tablePrefix.'messages_status mst ON msg.id=mst.msg_id
            INNER JOIN '.$this->usersTable.' us ON msg.sender_id=us.'.$this->usersTableKey.'
            WHERE mst.user_id = ? AND mst.status NOT IN (?, ?)
            ORDER BY msg.created_at DESC
            '
            , array($user_id, self::DELETED, self::ARCHIVED));
    }

    public function getUsersInConvs($convsIds)
    {
        return DB::select(
            '
                SELECT cu.conv_id, us.'.$this->usersTableKey.'
                FROM '.$this->tablePrefix.'conv_users cu
                INNER JOIN '.$this->usersTable.' us
                ON cu.user_id=us.'.$this->usersTableKey.'
                WHERE cu.conv_id IN('.$convsIds.')
            '
            , array());
    }
}