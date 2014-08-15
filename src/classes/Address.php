<?php
class Address {
    private $address = null;
    private $city = null;
    private $province = null;
    private $postal = null;
    private $country = null;

    public function __construct( $address, $city, $province, $postal, $country ) {
        $this->address = $address;
        $this->city = $city;
        $this->province = $province;
        $this->postal = $postal;
        $this->country = $country;
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
        if ( isset( $address ) && isset( $city ) && isset( $province ) && isset( $postal ) && isset( $country ) )
            return $address.$city.$province.$postal.$country;
    }


}
