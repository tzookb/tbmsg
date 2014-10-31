<?php

class ExampleTest extends TestCase {


	public function testBasicExample()
	{
        $tbmsg = new \Tzookb\TBMsg\TBMsg(new \Tzookb\TBMsg\Repositories\EloquentTBMsgRepository(), new \Illuminate\Events\Dispatcher());
		$this->assertTrue(1);
	}

}