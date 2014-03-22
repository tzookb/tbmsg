<?php

namespace Tzookb\TBMsg\Repositories\Eloquent;

use Tzookb\TBMsg\Repositories\Contracts\MessageStatusRepositoryInterface;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\MessageStatus;

class MessageStatusRepository implements MessageStatusRepositoryInterface {

    protected $messageStatus;

    public function __construct(MessageStatus $messageStatus)
    {
        $this->messageStatus = $messageStatus;
    }

    public function find($token)
    {

    }
}