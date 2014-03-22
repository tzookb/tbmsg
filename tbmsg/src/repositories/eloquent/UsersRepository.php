<?php

namespace Tzookb\TBMsg\Repositories\Eloquent;

use Tzookb\TBMsg\Repositories\Contracts\UsersRepositoryInterface;
use Tzookb\TBMsg\Repositories\Eloquent\Objects\User;

class UsersRepository implements UsersRepositoryInterface{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function find($token)
    {

    }
}