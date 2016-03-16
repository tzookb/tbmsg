<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 17:53
 */

namespace Tests\Domain\Helpers;


use Tzookb\TBMsg\Domain\Entities\TBMsgable;

class TestParticipant implements TBMsgable
{
    private $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getTbmsgIdentifyId()
    {
        return $this->id;
    }
}