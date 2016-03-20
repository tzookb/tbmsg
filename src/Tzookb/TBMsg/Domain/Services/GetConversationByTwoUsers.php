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
use Tzookb\TBMsg\Domain\Exceptions\ConversationNotFoundException;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;

class GetConversationByTwoUsers
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
     * @param $userIdA
     * @param $userIdB
     * @return int
     */
    public function handle($userIdA, $userIdB)
    {
        $res = $this->_conversationRepository->findByTwoUsers($userIdA, $userIdB);

        if (is_null($res)) {
            throw new ConversationNotFoundException;
        }

        return $res;
    }
}