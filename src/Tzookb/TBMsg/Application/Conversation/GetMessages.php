<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 18/03/16
 * Time: 5:50 PM
 */

namespace Tzookb\TBMsg\Application\Conversation;


class GetMessages
{
    /**
     * @var \Tzookb\TBMsg\Domain\Services\GetMessages
     */
    private $domainGetMessages;

    public function __construct(\Tzookb\TBMsg\Domain\Services\GetMessages $domainGetMessages)
    {

        $this->domainGetMessages = $domainGetMessages;
    }

    public function handle($userId, $convId)
    {
        return $this->domainGetMessages->handle($userId, $convId);
    }
}