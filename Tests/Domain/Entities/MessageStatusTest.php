<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 17:46
 */

namespace Tests\Domain\Entities;


use Carbon\Carbon;
use Tests\Domain\Helpers\TestParticipant;
use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Entities\MessageStatus;

class MessageStatusTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function check_basic_constructor()
    {
        $msgStatusId = 5;
        $msgRelatesTo = 1;
        $msgStatus = 1;
        $messageStatus = new MessageStatus($msgStatusId, $msgRelatesTo, $msgStatus);

        $this->assertEquals($msgStatusId, $messageStatus->getId());
        $this->assertEquals($msgRelatesTo, $messageStatus->getRelatesTo());
        $this->assertEquals($msgStatus, $messageStatus->getStatus());
    }
}