<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 17:46
 */

namespace Tzookb\TBMsg\Tests\Domain\Entities;


use Carbon\Carbon;
use Tests\Domain\Helpers\TestParticipant;
use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Entities\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function check_basic_constructor()
    {
        $creatorId = 55;
        $creator = $creatorId;
        $content = 'content here';
        $conv = new Message($creator, $content);

        $this->assertNull($conv->getId());
        $this->assertEquals($creator, $conv->getCreator());
        $this->assertEquals($creatorId, $conv->getCreator());
        $this->assertEquals($content, $conv->getContent());
    }

    /** @test */
    public function check_constructor_with_created()
    {
        $lastWeek = Carbon::now()->subWeek();
        $msg = new Message(55, 'dontcare', $lastWeek);
        $this->assertEquals($lastWeek, $msg->getCreated());
    }

}