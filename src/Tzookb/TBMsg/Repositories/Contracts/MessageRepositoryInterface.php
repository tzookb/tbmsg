<?php
namespace Tzookb\TBMsg\Repositories\Contracts;

interface MessageRepositoryInterface {

    public function find($token);

}