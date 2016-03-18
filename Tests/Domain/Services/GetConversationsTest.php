<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 17/03/16
 * Time: 16:44
 */

namespace Tests\Domain\Services;

use Tzookb\TBMsg\Application\DTO\MessageDTO;
use Tzookb\TBMsg\Application\DTO\ParticipantsList;
use Tzookb\TBMsg\Domain\Services\CreateConversation;
use Tzookb\TBMsg\Domain\Services\MessageConversation;
use Tests\TestCaseDb;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentMessageStatusRepository;
use Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus;

class GetConversationsTest extends TestCaseDb
{

    /**
     * @test
     */
    public function check_rows_created()
    {
        $convRepo = new EloquentConversationRepository($this->db);
        $msgStatusRepo = new EloquentMessageStatusRepository($this->db);

        $user1 = 1;
        $user2 = 2;
        $content = 'whatever';

        $participantsList = new ParticipantsList([$user1, $user2]);
        $createConversation = new CreateConversation($convRepo);
        $messageConversation = new MessageConversation($convRepo, $msgStatusRepo);

        $convId = $createConversation->handle($participantsList);

        $messageId = $messageConversation->handle(
            new MessageDTO($user1, $content),
            $convId
        );

        //TODO FIX GULY TEST
        $res = MessageStatus::all()->toArray();

        $this->assertEquals(1, $res[0]['id']);
        $this->assertEquals($user1, $res[0]['user_id']);
        $this->assertEquals($messageId, $res[0]['msg_id']);
        $this->assertEquals(\Tzookb\TBMsg\Domain\Entities\MessageStatus::READ, $res[0]['status']);

        $this->assertEquals(2, $res[1]['id']);
        $this->assertEquals($user2, $res[1]['user_id']);
        $this->assertEquals($messageId, $res[1]['msg_id']);
        $this->assertEquals(\Tzookb\TBMsg\Domain\Entities\MessageStatus::UNREAD, $res[1]['status']);


    }
}