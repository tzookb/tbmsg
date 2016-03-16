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
    private $_id;
    private $_relatesTo;
    private $_status;

    public function __construct($id, $relatesTo, $status)
    {
        $this->_id = $id;
        $this->_relatesTo = $relatesTo;
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
    public function getRelatesTo()
    {
        return $this->_relatesTo;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_status;
    }


}