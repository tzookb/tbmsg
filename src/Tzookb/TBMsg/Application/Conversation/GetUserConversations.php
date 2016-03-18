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

class GetUserConversations
{
    /**
     * @var DomainCreateConversation
     */
    private $domainCreateConversation;

    public function __construct(DomainCreateConversation $domainCreateConversation)
    {
        $this->domainCreateConversation = $domainCreateConversation;
    }

    /**
     * @param $userId
     * @return int
     */
    public function handle($userId)
    {
        return $this->domainCreateConversation->handle($participantsList);
    }
}