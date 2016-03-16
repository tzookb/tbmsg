<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 15:37
 */

namespace Tzookb\TBMsg\Domain\Entities;


use Carbon\Carbon;

class Message
{
    private $_id;
    private $_content;
    private $_created;
    private $_creator;

    public function __construct($creatorId, $content, $created = null, $id = null)
    {
        $this->_id = $id;
        $this->_content = $content;

        if (is_null($created)) {
            $created = Carbon::now();
        }
        $this->_created = $created;

        $this->_creator = $creatorId;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getCreator()
    {
        return $this->_creator;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function getCreated()
    {
        return $this->_created;
    }
}