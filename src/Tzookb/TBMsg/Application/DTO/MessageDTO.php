<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 13:40
 */

namespace Tzookb\TBMsg\Application\DTO;


class MessageDTO
{
    public $senderId;
    public $content;

    public function __construct($senderId, $content)
    {
        $this->senderId = $senderId;
        $this->content = $content;
    }

}