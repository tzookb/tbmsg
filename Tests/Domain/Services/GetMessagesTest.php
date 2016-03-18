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
use Tzookb\TBMsg\Domain\Services\GetConversations;
use Tzookb\TBMsg\Domain\Services\GetMessages;
use Tzookb\TBMsg\Domain\Services\MessageConversation;
use Tests\TestCaseDb;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentMessageStatusRepository;
use Tzookb\TBMsg\Persistence\Eloquent\Models\MessageStatus;

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
        $GetConversations = new GetConversations($convRepo);
        $messageConversation = new MessageConversation($convRepo, $msgStatusRepo);
        $getMessages = new GetMessages($convRepo, $msgStatusRepo);


        $convId = $createConversation->handle($participantsList);

        $messageId = $messageConversation->handle(
            new MessageDTO($user1, $content),
            $convId
        );


        $res = $getMessages->handle($user1, $convId);
        var_dump($res);

        $this->assertEquals(1, $res[0]->getId());
        $this->assertEquals($content, $res[0]->getContent());
        $this->assertEquals($user1, $res[0]->getCreator());




    }

}