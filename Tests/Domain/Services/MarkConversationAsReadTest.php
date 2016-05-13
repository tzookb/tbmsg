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
use Tzookb\TBMsg\Domain\Entities\Message;
use Tzookb\TBMsg\Domain\Services\CreateConversation;
use Tzookb\TBMsg\Domain\Services\GetMessages;
use Tzookb\TBMsg\Domain\Services\GetNumOfUnreadMessages;
use Tzookb\TBMsg\Domain\Services\MarkConversationAsRead;
use Tzookb\TBMsg\Domain\Services\MessageConversation;
use Tzookb\TBMsg\Tests\TestCaseDb;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentMessageStatusRepository;

class MarkConversationAsReadTest extends TestCaseDb
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
        $getNumOfUnreadMessages = new GetNumOfUnreadMessages($msgStatusRepo);
        $markConversationAsRead = new MarkConversationAsRead($convRepo);

        $convId = $createConversation->handle($participantsList);

        //send two messages
        $messageConversation->handle(new MessageDTO($user1, $content),$convId);
        $messageConversation->handle(new MessageDTO($user1, $content),$convId);

        //check there are 2 unread messages
        $res = $getNumOfUnreadMessages->handle($user2);
        $this->assertEquals(2, $res);

        //mark conversation as read
        $res = $markConversationAsRead->handle($convId, $user2);
        $this->assertEquals(2, $res);

        //now check there are no unread messages
        $res = $getNumOfUnreadMessages->handle($user2);
        $this->assertEquals(0, $res);
    }

}