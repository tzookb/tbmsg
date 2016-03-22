<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 19/03/16
 * Time: 17:31
 */

namespace Tzookb\TBMsg\Application\Conversation;


use Tzookb\TBMsg\Domain\Services\GetNumOfUnreadMessages as DomainGetNumOfUnreadMessages;

class GetNumOfUnreadMessages
{

    /**
     * @var DomainGetNumOfUnreadMessages
     */
    private $_getNumOfUnreadMessages;

    public function __construct(DomainGetNumOfUnreadMessages $getNumOfUnreadMessages)
    {
        $this->_getNumOfUnreadMessages = $getNumOfUnreadMessages;
    }

    public function handle($userId)
    {
        $res = $this->_getNumOfUnreadMessages->handle($userId);
        return $res;
    }
}