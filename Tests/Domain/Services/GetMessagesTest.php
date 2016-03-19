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
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Services\CreateConversation;
use Tzookb\TBMsg\Domain\Services\GetMessages;
use Tzookb\TBMsg\Domain\Services\MessageConversation;
use Tests\TestCaseDb;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentMessageStatusRepository;

class GetMessagesTest extends TestCaseDb
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
        $content = 'whatever';

        $participantsList = new ParticipantsList([$user1, $user2]);

        $createConversation = new CreateConversation($convRepo);
        $messageConversation = new MessageConversation($convRepo, $msgStatusRepo);
        $getMessages = new GetMessages($convRepo, $msgStatusRepo);


        $convId = $createConversation->handle($participantsList);

        $messageConversation->handle(
            new MessageDTO($user1, $content),
            $convId
        );

        $res = $getMessages->handle($user1, $convId);
        $this->assertEquals(1, $res[0]->getId());
        $this->assertEquals($content, $res[0]->getContent());
        $this->assertEquals($user1, $res[0]->getCreator());
        $this->assertEquals(Message::READ, $res[0]->getStatus());
        $this->assertEquals($user1, $res[0]->getUserRelated());

        $res = $getMessages->handle($user2, $convId);
        $this->assertEquals(1, $res[0]->getId());
        $this->assertEquals($content, $res[0]->getContent());
        $this->assertEquals($user1, $res[0]->getCreator());
        $this->assertEquals(Message::UNREAD, $res[0]->getStatus());
        $this->assertEquals($user2, $res[0]->getUserRelated());
    }

}