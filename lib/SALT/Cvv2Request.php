<?php
namespace SALT;
class Cvv2Request {
    private $code = null;

    public function __get( $property ) {
        if ( property_exists( $this, $property ) ) {
            return $this->$property;
        }

    }

    public function __set( $property, $value ) {
        if ( property_exists( $this, $property ) ) {
            $this->$property = $value;
        }
        return $this;
    }
    public function __construct( $code ) {
        $this->code = $code;
    }
    function __toString() {
        if ( isset( $code ) )
            return $code;
    }

}// Cvv2Request
