<?php
namespace Tzookb\TBMsg;


use DB;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\Conversation;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\ConversationUsers;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\Message;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\MessageStatus;

class TBMsg {

    const DELETED = 0;
    const UNREAD = 1;
    const READ = 2;
    const ARCHIVED = 3;

    public function __construct() {

    }

    public function getUserConversations($user_id) {
        $results = DB::select(
            '
            SELECT *
            FROM messages_status mst
            INNER JOIN messages msg
            ON mst.msg_id=msg.id
            WHERE mst.user_id = ?
            AND mst.status NOT IN (?,?)
            ORDER BY msg.created_at DESC
            LIMIT 1
            '
            , array($user_id, self::DELETED, self::ARCHIVED));
        return $results;
    }

    public function getConversationMessages($conv_id, $user_id) {
        $results = DB::select(
            '
            SELECT msg.id as msgID, msg.content, msg.created_at, us.id as userId, us.username, us.image
            FROM messages_status mst
            INNER JOIN messages msg
            ON mst.msg_id=msg.id
            INNER JOIN users us
            ON msg.sender_id=us.id
            WHERE msg.conv_id=?
            AND mst.user_id = ?
            AND mst.status NOT IN (?,?)
            '
            , array($conv_id, $user_id, self::DELETED, self::ARCHIVED));
        return $results;
    }

    /**
     * @param $userA_id
     * @param $userB_id
     * @return mixed -> id of conversation or false on not found
     */
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
        return false;
    }

    public function addMessageToConversation($conv_id, $user_id, $content) {
        //check if user of message is in conversation
        if ( !$this->isUserInConversation($conv_id, $user_id) )
            return false;

        //if so add new message
        $message = new Message();
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
    }


    public function createConversation( $users_ids=array() ) {
        if ( count($users_ids ) > 0 ) {
            //create new conv
            $conv = new Conversation();
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
        }
        return $conv;
    }

    public function markReadAllMessagesInConversation($conv_id, $user_id) {
        DB::statement(
            '
            UPDATE messages_status mst
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
            UPDATE messages_status mst
            SET mst.status='.self::DELETED.'
            WHERE mst.user_id=?
            AND mst.status=?
            AND mst.msg_id IN (
              SELECT msg.id
              FROM messages msg
              WHERE msg.conv_id=?
            )
            ',
            array($user_id, self::UNREAD, $conv_id)
        );
    }

    public function isUserInConversation($conv_id, $user_id) {
        $results = DB::select(
            '
            SELECT COUNT(cu.conv_id)
            FROM conv_users cu
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
            FROM conv_users cu
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
            FROM messages_status mst
            WHERE mst.user_id=?
            AND mst.status=?
            ',
            array($user_id, self::UNREAD)
        );

        return $results[0]->numOfUnread;
    }
} 