<?php



class SimpleTest extends TestCaseDb  {

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
		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$data = $this->tbmsg->addMessageToConversation($createdConvId, $user1, 'message content');

		//TODO
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