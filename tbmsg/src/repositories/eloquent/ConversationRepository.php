<?php

namespace Tzookb\TBMsg\Repositories\Eloquent;

use Tzookb\TBMsg\Repositories\Contracts\ConversationRepositoryInterface;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\Conversation;

class ConversationRepository implements ConversationRepositoryInterface {

    protected $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function find($token)
    {

    }
}