<?php
namespace Salt;
class Schedule {
    private $scheduleType = null;
    private $intervalLength = null;

    public function __construct( $type, $intervalLength ) {
        $this->scheduleType = $type;
        $this->intervalLength = $intervalLength;
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
        if ( isset( $scheduleType ) && isset( $intervalLength ) )
            return $scheduleType.$intervalLength;
    }

}