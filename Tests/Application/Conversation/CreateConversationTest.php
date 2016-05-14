<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 18:24
 */

namespace Tzookb\TBMsg\Tests\Application\Conversation;


use Tzookb\TBMsg\Application\Conversation;
use Tzookb\TBMsg\Domain\Services\CreateConversation;
use Tzookb\TBMsg\Tests\TestCaseDb;

class CreateConversationTest extends TestCaseDb
{
    /** @test */
    public function create_conversation()
    {
        $conversationRepository = \Mockery::mock('Tzookb\TBMsg\Domain\Repositories\ConversationRepository');
        $conversationRepository->shouldReceive('create')->once()->andReturn(1);

        $createConversation = new CreateConversation($conversationRepository);

        $this->app->bind(CreateConversation::class, function() use($createConversation) {
            return $createConversation;
        });

        $conversation = new Conversation($this->app);

        $res = $conversation->createConversation([1,2]);

        $this->assertEquals(1, $res);
    }


    public function tearDown()
    {
        \Mockery::close();
    }
}