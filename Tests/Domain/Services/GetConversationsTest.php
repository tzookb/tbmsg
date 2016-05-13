<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 17/03/16
 * Time: 16:44
 */

namespace Tzookb\TBMsg\Tests\Domain\Services;

use Tzookb\TBMsg\Application\DTO\MessageDTO;
use Tzookb\TBMsg\Application\DTO\ParticipantsList;
use Tzookb\TBMsg\Domain\Services\CreateConversation;
use Tzookb\TBMsg\Domain\Services\GetConversations;
use Tzookb\TBMsg\Domain\Services\MessageConversation;
use Tzookb\TBMsg\Tests\TestCaseDb;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentMessageStatusRepository;
use Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus;

class GetConversationsTest extends TestCaseDb
{

    /**
     * @test
     */
    public function get_several_conversations()
    {
        $convRepo = new EloquentConversationRepository($this->db);
        $msgStatusRepo = new EloquentMessageStatusRepository($this->db);

        $user1 = 1;
        $user2 = 2;
        $user3 = 3;
        $content = 'whatever';

        $participantsList1 = new ParticipantsList([$user1, $user2]);
        $participantsList2 = new ParticipantsList([$user1, $user3]);
        $createConversation = new CreateConversation($convRepo);
        $GetConversations = new GetConversations($convRepo);


        $createConversation->handle($participantsList1);
        $createConversation->handle($participantsList2);

        $convs = $GetConversations->handle($user1);

        $this->assertEquals(2, sizeof($convs));
        $this->assertEquals(1, $convs[0]);
        $this->assertEquals(2, $convs[1]);
    }

    /**
     * @test
     */
    public function no_conversations()
    {
        $convRepo = new EloquentConversationRepository($this->db);

        $user1 = 1;

        $GetConversations = new GetConversations($convRepo);

        $convs = $GetConversations->handle($user1);

        $this->assertEquals(0, sizeof($convs));
    }

}