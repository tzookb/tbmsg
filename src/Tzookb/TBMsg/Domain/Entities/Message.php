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
    const UNREAD = 0;
    const READ = 1;
    const ARCHIVED = 2;
    const DELETE = 3;

    private $_id;
    private $_content;
    private $_created;
    private $_creator;
    private $_status;
    private $_userRelated;


    public function __construct($creatorId, $content, $created = null, $id = null, $status = SELF::UNREAD, $userRelated = null)
    {
        $this->_id = $id;
        $this->_content = $content;

        if (is_null($created)) {
            $created = Carbon::now();
        }
        $this->_created = $created;

        $this->_creator = $creatorId;
        $this->_status = $status;
        $this->_userRelated = $userRelated;
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

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @return null
     */
    public function getUserRelated()
    {
        return $this->_userRelated;
    }


}