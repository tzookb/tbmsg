<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 13:37
 */

namespace Tzookb\TBMsg\Domain\Services;


use Tzookb\TBMsg\Application\DTO\ParticipantsList;
use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;

class CreateConversation
{
    /**
     * @var ConversationRepository
     */
    private $_conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->_conversationRepository = $conversationRepository;
    }

    /**
     * @param ParticipantsList $participantsList
     * @return integer
     */
    public function handle(ParticipantsList $participantsList)
    {
        $conversation = new Conversation($participantsList->getParticipants());
        return $this->_conversationRepository->create($conversation);
    }
}