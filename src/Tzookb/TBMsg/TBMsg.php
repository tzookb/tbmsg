<?php
namespace Tzookb\TBMsg;


use DB;
use Config;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;
use Tzookb\TBMsg\Application\Conversation;
use Tzookb\TBMsg\Application\Conversation\AddMessageToConversation;
use Tzookb\TBMsg\Application\Conversation\CreateConversation;
use Tzookb\TBMsg\Application\Conversation\GetMessages;
use Tzookb\TBMsg\Application\Conversation\GetNumOfUnreadMessages;
use Tzookb\TBMsg\Application\Conversation\GetUserConversations;

class TBMsg {

    const DELETED = 0;
    const UNREAD = 1;
    const READ = 2;
    const ARCHIVED = 3;
    protected $usersTable;
    protected $usersTableKey;
    protected $tablePrefix;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @param Container $container
     * @param Dispatcher $dispatcher
     */
    public function __construct(Container $container, Dispatcher $dispatcher) {
        $this->container = $container;
        $this->dispatcher = $dispatcher;
    }


    /**
     * @param $msgId
     * @param $userId
     * @param $status must be TBMsg consts: DELETED, UNREAD, READ, ARCHIVED
     * @throws \Exception
     */
    public function markMessageAs($msgId, $userId, $status) {
        throw new \Exception('todo if needed');
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as read
     * @throws \Exception
     */
    public function markMessageAsRead($msgId, $userId) {
        throw new \Exception('todo if needed');
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as unread
     * @throws \Exception
     */
    public function markMessageAsUnread($msgId, $userId) {
        throw new \Exception('todo if needed');
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as delete
     * @throws \Exception
     */
    public function markMessageAsDeleted($msgId, $userId) {
        throw new \Exception('todo if needed');
    }

    /**
     * @param $msgId
     * @param $userId
     * marks specific message as archived
     * @throws \Exception
     */
    public function markMessageAsArchived($msgId, $userId) {
        throw new \Exception('todo if needed');
    }

    /**
     * @param $user_id
     * @return Collection[Conversation]
     */
    public function getUserConversations($user_id) {
        /** @var GetUserConversations $getUserConversations */
        $getUserConversations = $this->container->make(GetUserConversations::class);
        $conversationIds = $getUserConversations->handle($user_id);
        return $conversationIds;
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
        /** @var GetMessages $getMessages */
        $getMessages = $this->container->make(GetMessages::class);
        $messages = $getMessages->handle($user_id, $conv_id);
        return $messages;
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
        /** @var Conversation $conversation */
        $conversation = $this->container->make(Conversation::class);
        $conversationId = $conversation->getConversationByTwoUsers($userA_id, $userB_id);
        return $conversationId;
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
        /** @var AddMessageToConversation $addMessageToConversation */
        $addMessageToConversation = $this->container->make(AddMessageToConversation::class);
        $res = $addMessageToConversation->handle($user_id, $content, $conv_id);
        return $res;
    }

    public function createConversation( $users_ids ) {
        /** @var CreateConversation $createConversation */
        $createConversation = $this->container->make(CreateConversation::class);
        $res = $createConversation->handle($users_ids);
        return $res;
    }

    public function sendMessageBetweenTwoUsers($senderId, $receiverId, $content)
    {
        throw new \Exception('todo if needed');
    }

    /**
     * @param $conv_id
     * @param $user_id
     *
     * mark all messages for specific user in specific conversation as read
     * @return int
     */
    public function markReadAllMessagesInConversation($conv_id, $user_id) {
        /** @var Conversation $conversation */
        $conversation = $this->container->make(Conversation::class);
        $res = $conversation->justReadConversation($conv_id, $user_id);
        return $res;
    }

    public function markUnreadAllMessagesInConversation($conv_id, $user_id) {
        throw new \Exception('todo if needed');
    }

    public function deleteConversation($conv_id, $user_id) {
        throw new \Exception('todo if needed');
    }

    public function isUserInConversation($conv_id, $user_id) {
        throw new \Exception('todo if needed');
    }

    public function getUsersInConversation($conv_id) {
        throw new \Exception('todo if needed');
    }

    /**
     * @param $user_id
     * @return int
     *
     * get number of unread messages for specific user
     */
    public function getNumOfUnreadMsgs($user_id) {
        /** @var GetNumOfUnreadMessages $getNumOfUnreadMessages */
        $getNumOfUnreadMessages = $this->container->make(GetNumOfUnreadMessages::class);
        $res = $getNumOfUnreadMessages->handle($user_id);
        return $res;
    }
}
