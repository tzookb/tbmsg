<?php



class SimpleTest extends TestCaseDb  {

	/**
	 * @test
	 */
	public function get_conversation_messages() {
		$user1 = 1;
		$user2 = 2;
		$data = $this->tbmsg->createConversation([$user1, $user2]);
		$createdConvId = $data->id;

		$this->tbmsg->addMessageToConversation($createdConvId, $user1, 'message content');

		$fullConv = $this->tbmsg->getConversationMessages($createdConvId, $user2);
		
		//TODO
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