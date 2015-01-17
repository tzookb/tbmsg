<?php
namespace Tzookb\TBMsg;


use DB;
use Config;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;
use Tzookb\TBMsg\Exceptions\ConversationNotFoundException;
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
    protected $tablePrefix;
    /**
     * @var Repositories\Contracts\iTBMsgRepository
     */
    protected $tbmRepo;
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param iTBMsgRepository $tbmRepo
     * @param Dispatcher $dispatcher
     */
    public function __construct(iTBMsgRepository $tbmRepo, Dispatcher $dispatcher) {
        $this->tbmRepo = $tbmRepo;
        $this->dispatcher = $dispatcher;
    }


    /**
     * @param $msgId
     * @param $userId
     * @param $status must be TBMsg consts: DELETED, UNREAD, READ, ARCHIVED
     */
    public function markMessageAs($msgId, $userId, $status) {
        $this->tbmRepo->markMessageAs($msgId, $userId, $status);
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as read
     */
    public function markMessageAsRead($msgId, $userId) {
        $this->tbmRepo->markMessageAsRead($msgId, $userId);
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as unread
     */
    public function markMessageAsUnread($msgId, $userId) {
        $this->tbmRepo->markMessageAsUnread($msgId, $userId);
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as delete
     */
    public function markMessageAsDeleted($msgId, $userId) {
        $this->tbmRepo->markMessageAsDeleted($msgId, $userId);
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as archived
     */
    public function markMessageAsArchived($msgId, $userId) {
        $this->tbmRepo->markMessageAsArchived($msgId, $userId);
    }

    /**
     * @param $user_id
     * @return Collection[Conversation]
     */
    public function getUserConversations($user_id) {
        $return = [];
        $conversations = new Collection();

        $convs = $this->tbmRepo->getConversations($user_id);

        $convsIds = [];
        foreach ( $convs as $conv ) {
            //this is for the query later
            $convsIds[] = $conv->conv_id;

            //this is for the return result
            $conv->users = [];
            $return[$conv->conv_id] = $conv;

            $conversation = new Conversation();
            $conversation->setId( $conv->conv_id );

            $message = new Message();
            $message->setId( $conv->msg_id );
            $message->setCreated( $conv->created_at );
            $message->setContent( $conv->content );
            $message->setStatus( $conv->status );
            $message->setSelf( $conv->self );
            $message->setSender( $conv->user_id );
            $conversation->addMessage($message);
            $conversations[ $conversation->getId() ] = $conversation;
        }
        $convsIds = implode(',',$convsIds);


        if ( $convsIds != '' ) {

            $usersInConvs = $this->tbmRepo->getUsersInConvs($convsIds);

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
     * @param bool $newToOld
     * @return Conversation
     *
     * return full conversation of user with his specific messages and messages statuses
     */
    public function getConversationMessages($conv_id, $user_id, $newToOld=true) {

        $results = $this->tbmRepo->getConversationMessages($conv_id, $user_id, $newToOld);

        $conversation = new Conversation();
        foreach ( $results as $row )
        {
            $msg = new Message();
            $msg->setId( $row->msg_id );
            $msg->setContent( $row->content );
            $msg->setCreated( $row->created_at );
            $msg->setSender( $row->user_id );
            $msg->setStatus($row->status);
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
     * @return int
     * @throws ConversationNotFoundException
     * returns the conversation id
     * or
     * -1 if conversation not found
     */
    public function getConversationByTwoUsers($userA_id, $userB_id) {
        try {
            $conv = $this->tbmRepo->getConversationByTwoUsers($userA_id, $userB_id);
        } catch (ConversationNotFoundException $ex) {
            return -1;
        }
        return $conv;
    }

    /**
     * @param $conv_id
     * @param $user_id
     * @param $content
     * @return array
     *
     * send message to conversation from specific user, and return the new message data
     */
    public function addMessageToConversation($conv_id, $user_id, $content) {
        $eventData = $this->tbmRepo->addMessageToConversation($conv_id, $user_id, $content);

        $this->dispatcher->fire('message.sent',[$eventData]);
        return  $eventData;
    }

    /**
     * @param array $users_ids
     * @throws Exceptions\NotEnoughUsersInConvException
     * @return ConversationEloquent
     */
    public function createConversation( $users_ids ) {
        $eventData = $this->tbmRepo->createConversation($users_ids);
        $this->dispatcher->fire('conversation.created',[$eventData]);
        return $eventData;
    }

    /**
     * @param $senderId
     * @param $receiverId
     * @param $content
     * @return array
     *
     * send message to specific user from specific user, and return the new message data
     * if conversation is not existing yet between users it will create it
     */
    public function sendMessageBetweenTwoUsers($senderId, $receiverId, $content)
    {
        //get conversation by two users
        try {
            $conv = $this->tbmRepo->getConversationByTwoUsers($senderId, $receiverId);
        } catch (ConversationNotFoundException $ex) {
            //if conversation doesnt exist, create it
            $conv = $this->tbmRepo->createConversation([$senderId, $receiverId]);
            $conv = $conv['convId'];
        }

        //add message to new conversation
        $eventData = $this->tbmRepo->addMessageToConversation($conv, $senderId, $content);

        $this->dispatcher->fire('message.sent',[$eventData]);
        return  $eventData;
    }

    /**
     * @param $conv_id
     * @param $user_id
     *
     * mark all messages for specific user in specific conversation as read
     */
    public function markReadAllMessagesInConversation($conv_id, $user_id) {
        $this->tbmRepo->markReadAllMessagesInConversation($conv_id, $user_id);
    }

    /**
     * @param $conv_id
     * @param $user_id
     *
     * mark all messages for specific user in specific conversation as unread
     */
    public function markUnreadAllMessagesInConversation($conv_id, $user_id) {
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
            [self::UNREAD, $user_id, self::READ, $conv_id, $user_id]
        );
    }

    public function deleteConversation($conv_id, $user_id) {
        $this->tbmRepo->deleteConversation($conv_id, $user_id);
    }

    /**
     * @param $conv_id
     * @param $user_id
     * @return bool
     *
     * checks if specific user is in specific conversation
     */
    public function isUserInConversation($conv_id, $user_id) {
        return $this->tbmRepo->isUserInConversation($conv_id, $user_id);
    }

    /**
     * @param $conv_id
     * @return array
     *
     * get an array of user id that participate in specific conversation
     */
    public function getUsersInConversation($conv_id) {
        return $this->tbmRepo->getUsersInConversation($conv_id);
    }

    /**
     * @param $user_id
     * @return int
     *
     * get number of unread messages for specific user
     */
    public function getNumOfUnreadMsgs($user_id) {
        return $this->tbmRepo->getNumOfUnreadMsgs($user_id);
    }
}
