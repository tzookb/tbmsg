<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 16:17
 */

namespace Tzookb\TBMsg\Domain\Repositories;


use Tzookb\TBMsg\Domain\Entities\Message;

interface MessageStatusRepository extends BaseRepository
{
    /**
     * @param Message $message
     * @return int
     */
    public function create(Message $message);

}