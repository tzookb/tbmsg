<?php

class TestCase extends \PHPUnit_Framework_TestCase {


    public function setUp()
    {
        parent::setUp(); // Don't forget this!

        $this->prepareForTests();

        //Route::enableFilters();
    }

    private function prepareForTests()
    {

    }
}
