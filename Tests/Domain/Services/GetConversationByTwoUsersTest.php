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
use Tzookb\TBMsg\Domain\Services\GetConversationByTwoUsers;
use Tzookb\TBMsg\Domain\Services\GetMessages;
use Tzookb\TBMsg\Domain\Services\MessageConversation;
use Tests\TestCaseDb;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentMessageStatusRepository;

class GetConversationByTwoUsersTest extends TestCaseDb
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


        $createConversation = new CreateConversation($convRepo);
        $messageConversation = new MessageConversation($convRepo, $msgStatusRepo);
        $getMessages = new GetMessages($convRepo, $msgStatusRepo);
        $getConversationByTwoUsers = new GetConversationByTwoUsers($convRepo);


        $convIdWithUser2 = $createConversation->handle(new ParticipantsList([$user1, $user2]));
        $convIdWithUser3 = $createConversation->handle(new ParticipantsList([$user1, $user3]));

        $res = $getConversationByTwoUsers->handle($user1, $user2);

    }

}