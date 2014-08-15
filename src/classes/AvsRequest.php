<?php
class AvsRequest {
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
    function __toString() {
        if ( isset( $code ) )
            return $code;
    }

    public function __construct( $code ) {
        $this->code = $code;
    }
}//AvsRequest
