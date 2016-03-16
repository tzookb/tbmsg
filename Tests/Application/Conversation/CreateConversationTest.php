<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 18:24
 */

namespace Tests\Application\Conversation;


use Tests\TestCaseDb;
use Tzookb\TBMsg\Application\Conversation\CreateConversation;

class CreateConversationTest extends TestCaseDb
{
    /** @test */
    public function create_conversation()
    {
        $conversationRepository = \Mockery::mock('Tzookb\TBMsg\Domain\Repositories\ConversationRepository');
        $conversationRepository->shouldReceive('create')->once()->andReturn(1);
        $createConversation = new CreateConversation(
            new \Tzookb\TBMsg\Domain\Services\CreateConversation($conversationRepository)
        );
        $res = $createConversation->handle([1,2]);

        $this->assertEquals(1, $res);
    }


    public function tearDown()
    {
        \Mockery::close();
    }
}