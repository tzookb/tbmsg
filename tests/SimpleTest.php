<?php


use Tzookb\TBMsg\Entities\Message;
use Tzookb\TBMsg\TBMsg;

class SimpleTest extends TestCaseDb  {

	/**
	 * @test
	 */
	public function mark_message_as_read_and_unread() {
		$user1 = 4;
		$user2 = 9;
		$content = 'default content';

		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, $content);

		$conv = $this->tbmsg->getConversationMessages($createdConvId, $user2);
		$message = $conv->getLastMessage();
		$this->assertEquals(TBMsg::UNREAD, $message->getStatus());

		$this->tbmsg->markMessageAsRead($message->getId(), $user2);

		$conv = $this->tbmsg->getConversationMessages($createdConvId, $user2);
		$message = $conv->getLastMessage();
		$this->assertEquals(TBMsg::READ, $message->getStatus());

		$this->tbmsg->markMessageAsUnread($message->getId(), $user2);

		$conv = $this->tbmsg->getConversationMessages($createdConvId, $user2);
		$message = $conv->getLastMessage();
		$this->assertEquals(TBMsg::UNREAD, $message->getStatus());
	}

	/**
	 * @test
	 */
	public function mark_unread_messages_as_read() {
		$user1 = 4;
		$user2 = 9;
		$content = 'default content';

		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, $content);
		$this->tbmsg->addMessageToConversation($createdConvId, $user1, $content);
		$this->assertEquals(2, $this->tbmsg->getNumOfUnreadMsgs($user2));

		$this->tbmsg->markReadAllMessagesInConversation($createdConvId, $user2);
		$this->assertEquals(0, $this->tbmsg->getNumOfUnreadMsgs($user2));
	}

	/**
	 * @test
	 */
	public function get_number_of_unread_messages() {
		$user1 = 4;
		$user2 = 9;
		$content = 'default content';

		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$this->assertEquals(0, $this->tbmsg->getNumOfUnreadMsgs($user2));

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, $content);
		$this->assertEquals(1, $this->tbmsg->getNumOfUnreadMsgs($user2));

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, $content);
		$this->assertEquals(2, $this->tbmsg->getNumOfUnreadMsgs($user2));
	}

	/**
	 * @test
	 */
	public function send_message_between_two_users() {
		$user1 = 4;
		$user2 = 9;
		$content = 'default content';

		$this->tbmsg->sendMessageBetweenTwoUsers($user1, $user2, $content);

		$data = $this->tbmsg->getConversationByTwoUsers($user1, $user2);
		$this->assertEquals(1, $data);
	}

	/**
	 * @test
	 */
	public function check_conversation_num_of_participants() {
		$user1 = 4;
		$user2 = 9;
		$user3 = 11;
		$user4 = 15;
		$user5 = 17;
		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$fullConv = $this->tbmsg->getConversationMessages($createdConvId, $user2);
		$this->assertEquals(2, $fullConv->getNumOfParticipants());

		//check 5 users
		$data = $this->tbmsg->createConversation([$user1, $user2, $user3, $user4, $user5]);
		$createdConvId = $data->id;

		$fullConv = $this->tbmsg->getConversationMessages($createdConvId, $user2);
		$this->assertEquals(5, $fullConv->getNumOfParticipants());
	}

	/**
	 * @test
	 */
	public function get_conversation_users() {
		$user1 = 4;
		$user2 = 9;
		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, 'message content');

		$fullConv = $this->tbmsg->getConversationMessages($createdConvId, $user2);

		$participants = $fullConv->getAllParticipants();
		$participants = array_flip($participants);
		$this->assertEquals(2, sizeof($participants));
		$this->assertTrue(isset($participants[$user1]));
		$this->assertTrue(isset($participants[$user2]));
	}

	/**
	 * @test
	 */
	public function check_conversation_type_is_couple() {
		$user1 = 4;
		$user2 = 9;
		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, 'message content');

		$fullConv = $this->tbmsg->getConversationMessages($createdConvId, $user2);

		$this->assertEquals('couple', $fullConv->getType());
	}

	/**
	 * @test
	 */
	public function add_message_to_conversation() {
		$user1 = 1;
		$user2 = 2;
		$msgContent = 'not relevant content';

		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, $msgContent);

		$conv = $this->tbmsg->getConversationMessages($createdConvId, $user2);

		$this->assertEquals(1, $conv->getNumOfMessages());

		/** @var Message $msg */
		$msg = $conv->getLastMessage();

		$this->assertEquals($msgContent, $msg->getContent());
		$this->assertEquals($user1, $msg->getSender());
		$this->assertEquals(0, $msg->getSelf());
		$this->assertEquals(TBMsg::UNREAD, $msg->getStatus());
	}

	/**
	 * @test
	 */
	public function get_conversation_by_2_users() {

		$data = $this->tbmsg->createConversation([1,5]);
		$createdConvId = $data->id;

		$foundConvId = $this->tbmsg->getConversationByTwoUsers(1,5);

		$this->assertEquals($createdConvId, $foundConvId);
	}

	/**
	 * @test
	 * @expectedException     Tzookb\TBMsg\Exceptions\NotEnoughUsersInConvException
	 */
	public function create_conversation_with_not_enough_users() {
		$data = $this->tbmsg->createConversation([1]);
	}

}