<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 18/03/16
 * Time: 11:19
 */

namespace Tzookb\TBMsg\Domain\Services;


use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;


class GetConversations
{
    /**
     * @var ConversationRepository
     */
    private $_conversationRepository;

    /**
     * MessageConversation constructor.
     * @param ConversationRepository $conversationRepository
     */
    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->_conversationRepository = $conversationRepository;
    }

    public function handle($userId)
    {
        return $this->_conversationRepository->allByUserId($userId);
    }
}