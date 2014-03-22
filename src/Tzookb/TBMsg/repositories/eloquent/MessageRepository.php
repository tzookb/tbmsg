<?php

namespace Tzookb\TBMsg\Repositories\Eloquent;

use Tzookb\TBMsg\Repositories\Contracts\MessageRepositoryInterface;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\Message;

class MessageRepository implements MessageRepositoryInterface {

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function find($token)
    {

    }
}