<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:17
 */

namespace Tzookb\TBMsg\Domain\Repositories;


use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Entities\MessageStatus;

interface MessageStatusRepository extends BaseRepository
{
    /**
     * @param MessageStatus $messageStatus
     * @return int
     */
    public function create(MessageStatus $messageStatus);

}