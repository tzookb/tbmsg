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

class ConversationTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function check_basic_constructor()
    {
        $donCareParticipant = 2;
        $conv = new Conversation([$donCareParticipant], []);
        $this->assertEquals([], $conv->getMessages());
        $this->assertEquals([$donCareParticipant], $conv->getParticipants());
        $this->assertEquals(time(), $conv->getCreated()->getTimestamp());
    }

    /** @test */
    public function check_constructor_with_created()
    {
        $lastWeek = Carbon::now()->subWeek();
        $conv = new Conversation([2], [], $lastWeek);
        $this->assertEquals($lastWeek, $conv->getCreated());
    }

    /** @test */
    public function check_constructor_with_id()
    {
        $id = 55;
        $conv = new Conversation([2], [], null, $id);
        $this->assertEquals($id, $conv->getId());
    }

    /** @test */
    public function check_constructor_with_participants()
    {
        $par1 = 1;
        $par2 = 2;

        $conv = new Conversation([$par1, $par2], []);

        $participants = $conv->getParticipants();

        $this->assertEquals(2, sizeof($participants));
    }

}