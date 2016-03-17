<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 16/03/16
 * Time: 12:51
 */

namespace Tests\Persistence\Eloquent;


use Tests\Domain\Helpers\TestParticipant;
use Tests\TestCaseDb;
use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;

class EloquentConversationRepositoryTest extends TestCaseDb
{

    /** @test */
    public function create_conversation_with_one_participant()
    {
        $service = $this->getRepository();

        $conversation = new Conversation([1]);
        $conversationId = $service->create($conversation);

        $participants = $service->allParticipants(1);


        $this->assertEquals(1, $conversationId);
        $this->assertEquals(1, sizeof($participants));
        $this->assertEquals(1, $participants[0]);
    }

    /** @test */
    public function create_conversation_with_two_participants()
    {
        $service = $this->getRepository();

        $conversation = new Conversation([1, 2]);
        $conversationId = $service->create($conversation);

        $participants = $service->allParticipants(1);

        $this->assertEquals(2, sizeof($participants));
        $this->assertEquals(1, $participants[0]);
        $this->assertEquals(2, $participants[1]);
    }

    /** @test */
    public function create_conversation_with_multiple_participants()
    {
        $service = $this->getRepository();

        $conversation = new Conversation([1,2,3,4,5]);
        $conversationId = $service->create($conversation);

        $participants = $service->allParticipants(1);

        $this->assertEquals(5, sizeof($participants));
        $this->assertEquals(1, $participants[0]);
        $this->assertEquals(2, $participants[1]);
        $this->assertEquals(3, $participants[2]);
        $this->assertEquals(4, $participants[3]);
        $this->assertEquals(5, $participants[4]);
    }

    /**
     * @return EloquentConversationRepository
     */
    public function getRepository()
    {
        $service = new EloquentConversationRepository($this->db);
        return $service;
    }


}