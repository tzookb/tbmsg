<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 19/03/16
 * Time: 17:32
 */

namespace Tzookb\TBMsg\Domain\Services;


use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;

class MarkConversationAsRead
{
    /**
     * @var ConversationRepository
     */
    private $_conversationRepository;

    /**
     * GetNumOfUnreadMessages constructor.
     * @param ConversationRepository $conversationRepository
     */
    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->_conversationRepository = $conversationRepository;
    }

    public function handle($convId, $userId)
    {
        return $this->_conversationRepository->markConversationAsRead($userId, $convId);
    }
}