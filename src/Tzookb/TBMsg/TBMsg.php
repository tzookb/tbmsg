<?php
namespace Tzookb\TBMsg;


use DB;
use Config;
use Illuminate\Support\Collection;
use Tzookb\TBMsg\Exceptions\NotEnoughUsersInConvException;
use Tzookb\TBMsg\Exceptions\UserNotInConvException;

use Tzookb\TBMsg\Entities\Conversation;
use Tzookb\TBMsg\Entities\Message;

use Tzookb\TBMsg\Models\Eloquent\Message as MessageEloquent;
use Tzookb\TBMsg\Models\Eloquent\Conversation as ConversationEloquent;
use Tzookb\TBMsg\Models\Eloquent\ConversationUsers;
use Tzookb\TBMsg\Models\Eloquent\MessageStatus;
use Tzookb\TBMsg\Repositories\Contracts\iTBMsgRepository;

class TBMsg {

    const DELETED = 0;
    const UNREAD = 1;
    const READ = 2;
    const ARCHIVED = 3;
    protected $usersTable;
    protected $usersTableKey;
    /**
     * @var Repositories\Contracts\iTBMsgRepository
     */
    protected $tbmRepo;

    public function __construct(iTBMsgRepository $tbmRepo) {
        $this->usersTable = Config::get('tbmsg.usersTable', 'users');
        $this->usersTableKey = Config::get('tbmsg.usersTableKey', 'id');
        $this->tbmRepo = $tbmRepo;
    }

    public function getUserConversations($user_id) {
        $return = array();
        $conversations = new Collection();

        $convs = DB::select(
            '
            SELECT msg.conv_id as conv_id, msg.created_at, msg.id msgId, msg.content, mst.status, mst.self, us.'.$this->usersTableKey.' userId
            FROM messages msg
            INNER JOIN (
                SELECT MAX(created_at) created_at
                FROM messages
                GROUP BY conv_id
            ) m2 ON msg.created_at = m2.created_at
            INNER JOIN messages_status mst ON msg.id=mst.msg_id
            INNER JOIN '.$this->usersTable.' us ON msg.sender_id=us.'.$this->usersTableKey.'
            WHERE mst.user_id = ? AND mst.status NOT IN (?, ?)
            ORDER BY msg.created_at DESC
            '
            , array($user_id, self::DELETED, self::ARCHIVED));

        $convsIds = array();
        foreach ( $convs as $conv ) {
            //this is for the query later
            $convsIds[] = $conv->conv_id;

            //this is for the return result
            $conv->users = array();
            $return[$conv->conv_id] = $conv;

            $conversation = new Conversation();
            $conversation->setId( $conv->conv_id );

            $message = new Message();
            $message->setId( $conv->msgId );
            $message->setCreated( $conv->created_at );
            $message->setContent( $conv->content );
            $message->setStatus( $conv->status );
            $message->setSelf( $conv->self );
            $message->setSender( $conv->userId );
            $conversation->addMessage($message);
            $conversations[ $conversation->getId() ] = $conversation;
        }
        $convsIds = implode(',',$convsIds);


        if ( $convsIds != '' ) {
            $usersInConvs = DB::select(
                '
                SELECT cu.conv_id, us.'.$this->usersTableKey.'
                FROM conv_users cu
                INNER JOIN '.$this->usersTable.' us
                ON cu.user_id=us.'.$this->usersTableKey.'
                WHERE cu.conv_id IN('.$convsIds.')
            '
                , array());

            foreach ( $usersInConvs as $usersInConv ) {
                if ( $user_id != $usersInConv->id ) {
                    $user = new \stdClass();
                    $user->id = $usersInConv->id;
                    //this is for the return result
                    $return[$usersInConv->conv_id]->users[$user->id] = $user;
                }
                $conversations[ $usersInConv->conv_id ]->addParticipant( $usersInConv->id );
            }
        }


        return $conversations;
    }

    /**
     * @param $conv_id
     * @param $user_id
     * @return Conversation
     */
    public function getConversationMessages($conv_id, $user_id, $newToOld=true) {
        if ( $newToOld )
            $orderBy = 'desc';
        else
            $orderBy = 'asc';
        $results = DB::select(
            '
            SELECT msg.id as msgId, msg.content, msg.created_at, us.'.$this->usersTableKey.' as userId
            FROM messages_status mst
            INNER JOIN messages msg
            ON mst.msg_id=msg.id
            INNER JOIN '.$this->usersTable.' us
            ON msg.sender_id=us.'.$this->usersTableKey.'
            WHERE msg.conv_id=?
            AND mst.user_id = ?
            AND mst.status NOT IN (?,?)
            ORDER BY msg.created_at '.$orderBy.'
            '
            , array($conv_id, $user_id, self::DELETED, self::ARCHIVED));

        $conversation = new Conversation();
        foreach ( $results as $row )
        {
            $msg = new Message();
            $msg->setId( $row->msgId );
            $msg->setContent( $row->content );
            $msg->setCreated( $row->created_at );
            $msg->setSender( $row->userId );
            $conversation->addMessage( $msg );
        }

        $usersInConv = $this->getUsersInConversation($conv_id);
        foreach ( $usersInConv as $userInConv )
            $conversation->addParticipant( $userInConv );


        return $conversation;
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
        return -1;
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
    }


    /**
     * @param array $users_ids
     * @throws Exceptions\NotEnoughUsersInConvException
     * @return ConversationEloquent
     */
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
            return $conv;
        } else
            throw new NotEnoughUsersInConvException;
    }

    public function sendMessageBetweenTwoUsers($senderId, $receiverId, $content)
    {
        //get conversation by two users
        $conv = $this->getConversationByTwoUsers($senderId, $receiverId);

        //if conversation doesnt exist, create it
        if ( $conv == -1 )
        {
            $conv = $this->createConversation([$senderId, $receiverId]);
            $conv = $conv->id;
        }

        //add message to new conversation
        $this->addMessageToConversation($conv, $senderId, $content);
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
