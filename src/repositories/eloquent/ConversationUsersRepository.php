<?php

namespace Tzookb\TBMsg\Repositories\Eloquent;

use Tzookb\TBMsg\Repositories\Contracts\ConversationUsersRepositoryInterface;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\ConversationUsers;

class ConversationUsersRepository implements ConversationUsersRepositoryInterface {

    protected $conversationUsers;

    public function __construct(ConversationUsers $conversation)
    {
        $this->conversationUsers = $conversationUsers;
    }

    public function find($token)
    {

    }
}