<?php

use \SALT\Address;

class AddressTest extends PHPUnit_Framework_TestCase {

    protected $address;

    protected function setUp() {
        $this->address = new Address(
            "89 De Boom Street",
            "San Francisco",
            "California",
            "94107",
            "USA"
            );
    }

    protected function tearDown() {
        unset($this->address);
    }

    public function testAddress() {
        $expected = "89 De Boom Street";
        $this->assertEquals($expected, $this->address->address);
    }

    public function testCity() {
         $expected = "San Francisco";
        $this->assertEquals($expected, $this->address->city);
    }

    public function testProvince() {
         $expected = "California";
        $this->assertEquals($expected, $this->address->province);
    }

    public function testPostal() {
         $expected = "94107";
        $this->assertEquals($expected, $this->address->postal);
    }

    public function testCountry() {
         $expected = "USA";
        $this->assertEquals($expected, $this->address->country);
    }

}