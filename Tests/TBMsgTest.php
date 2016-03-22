<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 22/03/16
 * Time: 11:38
 */

namespace Tests;


use Tests\Helpers\MockDispatcher;
use Tzookb\TBMsg\TBMsg;

class TBMsgTest extends TestCaseDb
{
    /**
     * @test
     */
    public function get_user_conversations()
    {
        $mockDispatcher = new MockDispatcher;

        //$conversationRepository = \Mockery::mock(MockDispatcher::class);
        //$conversationRepository->shouldReceive('create')->once()->andReturn(1);
        //$tbmsg = new TBMsg();
        //$tbmsg->getUserConversations(1);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}