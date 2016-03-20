<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 19/03/16
 * Time: 18:36
 */

namespace Tzookb\TBMsg\Application;


use Illuminate\Contracts\Foundation\Application;
use Tzookb\TBMsg\Domain\Services\GetConversationByTwoUsers;
use Tzookb\TBMsg\Domain\Services\MarkConversationAsRead;

class Conversation
{

    /**
     * @var Application
     */
    private $_app;

    public function __construct(Application $app)
    {
        $this->_app = $app;
    }

    public function justReadConversation($convId, $userId)
    {
        /** @var MarkConversationAsRead $markConversationAsRead */
        $markConversationAsRead = $this->_app->make(MarkConversationAsRead::class);

        return $markConversationAsRead->handle($convId, $userId);
    }

    public function getConversationByTwoUsers($userIdA, $userIdB)
    {
        /** @var GetConversationByTwoUsers $getConversationByTwoUser */
        $getConversationByTwoUser = $this->_app->make(GetConversationByTwoUsers::class);

        return $getConversationByTwoUser->handle($userIdA, $userIdB);
    }
}