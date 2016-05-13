<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:13
 */

namespace Tzookb\TBMsg\Application\Conversation;


use Tzookb\TBMsg\Application\DTO\MessageDTO;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Domain\Services\MessageConversation;

class AddMessageToConversation
{

    /**
     * @var MessageConversation
     */
    private $messageConversation;

    public function __construct(MessageConversation $messageConversation)
    {
        $this->messageConversation = $messageConversation;
    }

    public function handle($senderId, $content, $conversationId)
    {
        $message = new MessageDTO($senderId, $content);
        $res = $this->messageConversation->handle($message, $conversationId);
        return $res;
    }
}