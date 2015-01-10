<?php


use Tzookb\TBMsg\Entities\Conversation;
use Tzookb\TBMsg\Entities\Message;

class ConversationObjectTest extends TestCaseDb  {

	/**
	 * @test
	 */
	public function check_get_type_is_group() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(4);
		$msg2 = new Message();
		$msg2->setSender(5);
		$msg3 = new Message();
		$msg3->setSender(9);

		$obj->addMessage($msg1);
		$obj->addMessage($msg2);
		$obj->addMessage($msg3);

		$type = $obj->getType();


		$this->assertEquals(Conversation::GROUP, $type);
	}

	/**
	 * @test
	 */
	public function check_get_type_is_couple() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(5);
		$msg3 = new Message();
		$msg3->setSender(8);

		$obj->addMessage($msg1);
		$obj->addMessage($msg2);
		$obj->addMessage($msg3);

		$type = $obj->getType();

		$this->assertEquals(Conversation::COUPLE, $type);
	}

	/**
	 * @test
	 */
	public function get_last_message() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(5);
		$msg3 = new Message();
		$msg3->setSender(8);

		$obj->addMessage($msg1);
		$obj->addMessage($msg2);
		$obj->addMessage($msg3);

		$lastMsg = $obj->getLastMessage();

		$this->assertEquals($msg3->getSender(), $lastMsg->getSender());
	}

	/**
	 * @test
	 */
	public function get_first_message() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(8);
		$msg3 = new Message();
		$msg3->setSender(8);

		$obj->addMessage($msg1);
		$obj->addMessage($msg2);
		$obj->addMessage($msg3);

		$firstMsg = $obj->getFirstMessage();

		$this->assertEquals($msg1->getSender(), $firstMsg->getSender());
	}

	/**
	 * @test
	 */
	public function get_all_messages() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(8);
		$msg3 = new Message();
		$msg3->setSender(8);

		$obj->addMessage($msg1);
		$obj->addMessage($msg2);
		$obj->addMessage($msg3);

		$msgs = $obj->getAllMessages();

		$this->assertEquals(3, $msgs->count());
	}

	/**
	 * @test
	 */
	public function get_other_participant_check() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(8);

		$obj->addMessage($msg1);
		$obj->addMessage($msg2);

		$this->assertEquals(8, $obj->getTheOtherParticipant(5));
	}

	/**
	 * @test
	 */
	public function get_num_of_participants_check() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(8);
		$msg3 = new Message();
		$msg3->setSender(11);
		$msg4 = new Message();
		$msg4->setSender(11);

		$obj->addMessage($msg1);

		$this->assertEquals(1, $obj->getNumOfParticipants());

		$obj->addMessage($msg2);

		$this->assertEquals(2, $obj->getNumOfParticipants());

		$obj->addMessage($msg3);

		$this->assertEquals(3, $obj->getNumOfParticipants());

		$obj->addMessage($msg4);

		$this->assertEquals(3, $obj->getNumOfParticipants());
	}

	/**
	 * @test
	 */
	public function get_all_participants_check() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(8);
		$msg3 = new Message();
		$msg3->setSender(11);
		$msg4 = new Message();
		$msg4->setSender(11);

		$obj->addMessage($msg1);
		$participants = $obj->getAllParticipants();
		$this->assertEquals(1, sizeof($participants));
		$this->assertEquals(['5'=>0], array_flip($participants));

		$obj->addMessage($msg2);
		$participants = $obj->getAllParticipants();
		$this->assertEquals(2, sizeof($participants));
		$this->assertEquals(['5'=>0, '8'=>1], array_flip($participants));

		$obj->addMessage($msg3);
		$participants = $obj->getAllParticipants();
		$this->assertEquals(3, sizeof($participants));
		$this->assertEquals(['5'=>0, '8'=>1, '11'=>2], array_flip($participants));

		$obj->addMessage($msg4);
		$participants = $obj->getAllParticipants();
		$this->assertEquals(3, sizeof($participants));
		$this->assertEquals(['5'=>0, '8'=>1, '11'=>2], array_flip($participants));
	}

	/**
	 * @test
	 */
	public function get_num_of_messages_check() {
		$obj = new Conversation();

		$msg1 = new Message();
		$msg1->setSender(5);
		$msg2 = new Message();
		$msg2->setSender(8);
		$msg3 = new Message();
		$msg3->setSender(11);

		$obj->addMessage($msg1);

		$this->assertEquals(1, $obj->getNumOfMessages());

		$obj->addMessage($msg2);

		$this->assertEquals(2, $obj->getNumOfMessages());

		$obj->addMessage($msg3);

		$this->assertEquals(3, $obj->getNumOfMessages());
	}

	/**
	 * @test
	 * @expectedException     PHPUnit_Framework_Error
	 */
	public function add_not_valid_message_to_conv() {
		$obj = new Conversation();
		$obj->addMessage('sdfsdf');
	}

	/**
	 * @test
	 */
	public function check_set_get_created() {
		$obj = new Conversation();
		$obj->setCreated('created date');
		$this->assertEquals('created date', $obj->getCreated());
	}

	/**
	 * @test
	 */
	public function check_set_get_id() {
		$obj = new Conversation();
		$obj->setId(1);
		$this->assertEquals(1, $obj->getId());
	}


}