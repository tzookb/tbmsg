<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 15:52
 */

namespace Tzookb\TBMsg\Domain\Entities;


interface TBMsgable
{
    /**
     * return the identifier of the user who uses the messages system,
     * this id will be used to connecting messages and conversation to
     * the implementing object.
     *
     * @return mixed
     */
    public function getTbmsgIdentifyId();

}