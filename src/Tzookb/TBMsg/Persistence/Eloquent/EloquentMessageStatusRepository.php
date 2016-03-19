<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:40
 */

namespace Tzookb\TBMsg\Persistence\Eloquent;


use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository;

class EloquentMessageStatusRepository extends EloquentBaseRepository implements MessageStatusRepository
{

    /**
     * @param Message $message
     * @return int
     */
    public function create(Message $message)
    {
        $eloquentMessageStatus = new \Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus();
        $eloquentMessageStatus->user_id = $message->getUserRelated();
        $eloquentMessageStatus->msg_id = $message->getId();
        $eloquentMessageStatus->self = 0;
        $eloquentMessageStatus->status = $message->getStatus();

        $eloquentMessageStatus->save();
        return $eloquentMessageStatus->id;
    }
}