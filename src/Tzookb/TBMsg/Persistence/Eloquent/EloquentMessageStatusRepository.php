<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:40
 */

namespace Tzookb\TBMsg\Persistence\Eloquent;


use Tzookb\TBMsg\Domain\Entities\MessageStatus;
use Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository;

class EloquentMessageStatusRepository extends EloquentBaseRepository implements MessageStatusRepository
{

    /**
     * @param $msgId
     * @param MessageStatus $messageStatus
     * @return int
     */
    public function create($msgId, MessageStatus $messageStatus)
    {
        $eloquentMessageStatus = new \Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus();
        $eloquentMessageStatus->user_id = $messageStatus->getUserId();
        $eloquentMessageStatus->msg_id = $msgId;
        $eloquentMessageStatus->self = 0;
        $eloquentMessageStatus->status = $messageStatus->getStatus();

        $eloquentMessageStatus->save();
        return $eloquentMessageStatus->id;
    }
}