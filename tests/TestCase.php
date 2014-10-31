<?php
use Mockery;
class TestCase extends \PHPUnit_Framework_TestCase {


    public function setUp()
    {
        parent::setUp(); // Don't forget this!

        $this->prepareForTests();

        //Route::enableFilters();
    }

    private function prepareForTests()
    {

        //$service = m::mock('service');
        //$service->shouldReceive('readTemp')->times(3)->andReturn(10, 12, 14);
    }

    public function tearDown()
    {
        //Mockery::close();
    }
}
