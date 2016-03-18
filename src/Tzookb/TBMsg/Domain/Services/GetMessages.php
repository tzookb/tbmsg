<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 15:35
 */

namespace Tzookb\TBMsg\Domain\Services;


use Tzookb\TBMsg\Application\DTO\MessageDTO;
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Entities\MessageStatus;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository;

class GetMessages
{
    /**
     * @var ConversationRepository
     */
    private $_conversationRepository;

    /**
     * @var MessageStatusRepository
     */
    private $_messageStatusRepository;

    /**
     * MessageConversation constructor.
     * @param ConversationRepository $conversationRepository
     * @param MessageStatusRepository $messageStatusRepository
     */
    public function __construct(ConversationRepository $conversationRepository, MessageStatusRepository $messageStatusRepository)
    {
        $this->_conversationRepository = $conversationRepository;
        $this->_messageStatusRepository = $messageStatusRepository;
    }

    public function handle($userId, $conversationId)
    {
        $messages = [];
        $results = $this->_conversationRepository->getMessagesOfConversationForUser($userId, $conversationId);

        foreach ($results as $result) {
            $messages[] = new Message($result['sender_id'], $result['content'], $result['created_at'], $result['id']);
        }
        return $messages;
    }
}