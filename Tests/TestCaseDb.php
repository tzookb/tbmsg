<?php

namespace Tzookb\TBMsg\Tests;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;


class TestCaseDb extends \PHPUnit_Framework_TestCase {

    const DONT_CARE = 1;

	protected $db;
	/** @var \Tzookb\TBMsg\TBMsg */
	protected $tbmsg;

	/** @var  Container */
	protected $app;

	public function setUp() {
		parent::setUp(); // Don't forget this!

		$this->app = new Container();

		\League\FactoryMuffin\Facade::getFaker()->unique($reset = true);
		$this->initDb();
	}

	protected function initDb() {
		$capsule = new Manager();

		$capsule->addConnection([
			'driver'    => 'sqlite',
			'database'  => ':memory:',
			'prefix'    => '',
			'fetch'		=> \PDO::FETCH_CLASS
		]);
		$capsule->getConnection()->setFetchMode(\PDO::FETCH_CLASS);

		$capsule->setAsGlobal();
		$capsule->bootEloquent();

		$this->db = $capsule->getDatabaseManager();

		$this->createTables();

		$this->initMuffing();
		return $this->db;
	}
	protected function createTables() {
		Manager::schema()->create('tbm_conv_users', function($table)
		{
			$table->integer('conv_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->boolean('active')->default(true);

			$table->primary(array('conv_id', 'user_id'));
		});

		Manager::schema()->create('tbm_conversations', function($table)
		{
			$table->increments('id');
			$table->string('title', 50)->default('');

			$table->softDeletes();
			$table->timestamps();
		});

		Manager::schema()->create('tbm_messages', function($table)
		{
			$table->increments('id');
			$table->integer('sender_id');
			$table->integer('conv_id');
			$table->text('content');
			$table->timestamps();

			$table->index('sender_id');
			$table->index('conv_id');
		});

		Manager::schema()->create('tbm_message_statuses', function($table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('msg_id');
			$table->boolean('self');
			$table->integer('status');

			$table->index('msg_id');
		});
	}

	protected function initMuffing() {
		\League\FactoryMuffin\Facade::define('Tzookb\TBMsg\Models\Eloquent\Conversation', array(
			//'id' => 'int',
			'deleted_at' => 'dateTime',
			'created_at' => 'dateTime',
			'updated_at' => 'dateTime',
		));

		\League\FactoryMuffin\Facade::define('Tzookb\TBMsg\Models\Eloquent\ConversationUsers', array(
			'conv_id' => 'factory|Tzookb\TBMsg\Models\Eloquent\Conversation',
			'user_id' => 'int'
		));

		\League\FactoryMuffin\Facade::define('Tzookb\TBMsg\Models\Eloquent\Message', array(
			'sender_id' => 'int',
			'conv_id' => 'factory|Tzookb\TBMsg\Models\Eloquent\Conversation',
			'content' => 'int',
			'created_at' => 'int',
			'updated_at' => 'int',
		));


		\League\FactoryMuffin\Facade::define('Tzookb\TBMsg\Models\Eloquent\MessageStatus', array(
			'user_id' => 'int',
			'msg_id' => 'factory|Tzookb\TBMsg\Models\Eloquent\Message',
			'self' => 'int',
			'status' => 'int',
		));

	}

}