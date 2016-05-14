<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 19/03/16
 * Time: 18:36
 */

namespace Tzookb\TBMsg\Application;


use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Tzookb\TBMsg\Application\DTO\MessageDTO;
use Tzookb\TBMsg\Application\DTO\ParticipantsList;
use Tzookb\TBMsg\Domain\Services\CreateConversation;
use Tzookb\TBMsg\Domain\Services\GetConversationByTwoUsers;
use Tzookb\TBMsg\Domain\Services\GetConversations;
use Tzookb\TBMsg\Domain\Services\GetMessages;
use Tzookb\TBMsg\Domain\Services\GetNumOfUnreadMessages;
use Tzookb\TBMsg\Domain\Services\MarkConversationAsRead;
use Tzookb\TBMsg\Domain\Services\MessageConversation;

class Conversation
{

    /**
     * @var Application
     */
    private $_cont;

    public function __construct(Container $cont)
    {
        $this->_cont = $cont;
    }

    public function justReadConversation($convId, $userId)
    {
        /** @var MarkConversationAsRead $markConversationAsRead */
        $markConversationAsRead = $this->_cont->make(MarkConversationAsRead::class);

        return $markConversationAsRead->handle($convId, $userId);
    }

    public function getConversationByTwoUsers($userIdA, $userIdB)
    {
        /** @var GetConversationByTwoUsers $getConversationByTwoUser */
        $getConversationByTwoUser = $this->_cont->make(GetConversationByTwoUsers::class);

        return $getConversationByTwoUser->handle($userIdA, $userIdB);
    }

    public function getUserConversations($userId)
    {
        /** @var GetConversations $getConversations */
        $getConversations = $this->_cont->make(GetConversations::class);
        return $getConversations->handle($userId);
    }

    public function getNumOfUnreadMessages($userId)
    {
        /** @var GetNumOfUnreadMessages $getNumOfUnreadMessages */
        $getNumOfUnreadMessages = $this->_cont->make(GetNumOfUnreadMessages::class);
        return $getNumOfUnreadMessages->handle($userId);
    }

    public function getMessages($userId, $convId)
    {
        /** @var GetMessages $getMessages */
        $getMessages = $this->_cont->make(GetMessages::class);
        return $getMessages->handle($userId, $convId);
    }

    public function createConversation(array $participants)
    {
        /** @var CreateConversation $createConversation */
        $createConversation = $this->_cont->make(CreateConversation::class);

        $participantsList = new ParticipantsList($participants);
        return $createConversation->handle($participantsList);
    }

    public function addMessageToConversation($senderId, $content, $conversationId)
    {
        /** @var MessageConversation $messageConversation */
        $messageConversation = $this->_cont->make(MessageConversation::class);

        $message = new MessageDTO($senderId, $content);
        $res = $messageConversation->handle($message, $conversationId);
        return $res;
    }
}