<?php
class Cvv2Response {
    private $code;
    private $message;
    public function __construct( $code, $message ) {
        $this->code = $code;
        $this->message = $message;
    }

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

    function __toString() {
        if ( isset( $code ) && isset( $message ) )
            return $code.$message;
    }

}// Cvv2Response
