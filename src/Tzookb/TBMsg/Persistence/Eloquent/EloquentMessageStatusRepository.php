<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:40
 */

namespace Tzookb\TBMsg\Persistence\Eloquent;


use Illuminate\Database\DatabaseManager;
use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Entities\MessageStatus;
use Tzookb\TBMsg\Domain\Entities\TBMsgable;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository;
use Tzookb\TBMsg\Persistence\Eloquent\Models\ConversationUsers;

class EloquentMessageStatusRepository extends EloquentBaseRepository implements MessageStatusRepository
{

    /**
     * @param MessageStatus $messageStatus
     * @return int
     */
    public function create(MessageStatus $messageStatus)
    {
        $eloquentMessageStatus = new \Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus();
        $eloquentMessageStatus->user_id = $messageStatus->getRelatesTo();
        $eloquentMessageStatus->msg_id = $messageStatus->getMsgId(); //TODO, not exist
        $eloquentMessageStatus->self = $messageStatus->isSelf(); //TODO, not exist
        $eloquentMessageStatus->status = $messageStatus->getStatus();

        $eloquentMessageStatus->save();
        return $eloquentMessageStatus->id;
    }
}