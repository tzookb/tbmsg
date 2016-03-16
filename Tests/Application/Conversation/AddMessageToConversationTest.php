<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 18:24
 */

namespace Tests\Application\Conversation;


use Tests\TestCaseDb;
use Tests\Domain\Helpers\TestParticipant;
use Tzookb\TBMsg\Application\Conversation\AddMessageToConversation;
use Tzookb\TBMsg\Domain\Entities\Conversation;
use Tzookb\TBMsg\Domain\Services\MessageConversation;


class AddMessageToConversationTest extends TestCaseDb
{
    /** @test */
    public function add_msg_to_conversation()
    {
        $demoParticipantsId = [1, 2];

        $conversationRepository = \Mockery::mock('Tzookb\TBMsg\Domain\Repositories\ConversationRepository');
        $conversationRepository->shouldReceive('findById')->once()->andReturn(1);
        $conversationRepository->shouldReceive('allParticipants')->once()->andReturn($demoParticipantsId);
        $conversationRepository->shouldReceive('beginTransaction')->once()->andReturn();
        $conversationRepository->shouldReceive('addMessage')->once()->andReturn();
        $conversationRepository->shouldReceive('commit')->once()->andReturn();

        $messageStatusRepository = \Mockery::mock('Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository');
        $messageStatusRepository->shouldReceive('create')->times(sizeof($demoParticipantsId))->andReturn([1,1]);

        $messageConversation = new MessageConversation($conversationRepository, $messageStatusRepository);
        $addMessageToConversation = new AddMessageToConversation($messageConversation);

        $creatorId = 1;
        $conversationId = 1;
        $content = 'whatever';

        $res = $addMessageToConversation->handle($creatorId, $content, $conversationId);
        $this->assertTrue($res);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}