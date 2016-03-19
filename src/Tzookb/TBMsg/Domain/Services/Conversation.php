<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 19/03/16
 * Time: 18:36
 */

namespace Tzookb\TBMsg\Domain\Services;


use Illuminate\Contracts\Foundation\Application;

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
}