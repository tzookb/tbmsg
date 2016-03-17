<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 15:37
 */

namespace Tzookb\TBMsg\Domain\Entities;


class MessageStatus
{
    const UNREAD = 0;
    const READ = 1;
    const ARCHIVED = 2;
    const DELETE = 3;

    private $_id;
    private $_userId;
    private $_status;

    public function __construct($userId, $status, $id = null)
    {
        $this->_id = $id;
        $this->_userId = $userId;
        $this->_status = $status;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_status;
    }


}