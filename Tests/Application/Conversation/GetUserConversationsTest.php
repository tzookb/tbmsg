<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 15/03/16
 * Time: 18:24
 */

namespace Tzookb\TBMsg\Tests\Application\Conversation;


use Tzookb\TBMsg\Application\Conversation;
use Tzookb\TBMsg\Application\Conversation\GetUserConversations;
use Tzookb\TBMsg\Domain\Services\GetConversations;
use Tzookb\TBMsg\Tests\TestCaseDb;

class GetUserConversationsTest extends TestCaseDb
{
    /** @test */
    public function get_user_conversations()
    {
        $conversationRepository = \Mockery::mock('Tzookb\TBMsg\Domain\Repositories\ConversationRepository');
        $conversationRepository->shouldReceive('allByUserId')->once()->andReturn(1);

        $getConversations = new GetConversations($conversationRepository);

        $this->app->bind(GetConversations::class, function() use($getConversations) {
            return $getConversations;
        });

        $conversation = new Conversation($this->app);

        $res = $conversation->getUserConversations(1);

        $this->assertEquals(1, $res);
    }


    public function tearDown()
    {
        \Mockery::close();
    }
}