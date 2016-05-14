<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 17/03/16
 * Time: 16:44
 */

namespace Tzookb\TBMsg\Tests\Domain\Services;


use Tzookb\TBMsg\Domain\Exceptions\ConversationNotFoundException;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Domain\Services\GetConversationByTwoUsers;
use Tzookb\TBMsg\Tests\TestCaseDb;

class GetConversationByTwoUsersTest extends TestCaseDb
{

    /** @test */
    public function get_conversation_ok()
    {
        $convId = 1111;

        $conversationRepository = \Mockery::mock(ConversationRepository::class);
        $conversationRepository->shouldReceive('findByTwoUsers')->once()->andReturn($convId);

        $getConversationByTwoUsers = new GetConversationByTwoUsers($conversationRepository);

        try {
            $getConversationByTwoUsers->handle(self::DONT_CARE, self::DONT_CARE);

        } catch (ConversationNotFoundException $ex) {
            $this->assertEquals(ConversationNotFoundException::class, get_class($ex));
        }
    }

    /**
     * @test
     * @expectedException \Tzookb\TBMsg\Domain\Exceptions\ConversationNotFoundException
     */
    public function no_conversation_exists()
    {
        $conversationRepository = \Mockery::mock(ConversationRepository::class);
        $conversationRepository->shouldReceive('findByTwoUsers')->once()->andReturn(null);

        $getConversationByTwoUsers = new GetConversationByTwoUsers($conversationRepository);

        $getConversationByTwoUsers->handle(self::DONT_CARE, self::DONT_CARE);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}