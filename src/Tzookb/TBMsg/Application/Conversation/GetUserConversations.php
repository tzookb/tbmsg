<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:13
 */

namespace Tzookb\TBMsg\Application\Conversation;


use Tzookb\TBMsg\Application\DTO\ParticipantsList;
use Tzookb\TBMsg\Domain\Services\CreateConversation as DomainCreateConversation;
use Tzookb\TBMsg\Domain\Services\GetConversations;

class GetUserConversations
{

    /**
     * @var GetConversations
     */
    private $getConversations;

    public function __construct(GetConversations $getConversations)
    {

        $this->getConversations = $getConversations;
    }

    /**
     * @param $userId
     * @return int
     */
    public function handle($userId)
    {
        return $this->getConversations->handle($userId);
    }
}