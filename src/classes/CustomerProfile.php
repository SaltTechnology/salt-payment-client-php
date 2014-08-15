<?php
class CustomerProfile {
    private $legalName = null;
    private $tradeName = null;
    private $website = null;
    private $firstName = null;
    private $lastName = null;
    private $phoneNumber = null;
    private $faxNumber = null;
    private $address1 = null;
    private $address2 = null;
    private $city = null;
    private $province = null;
    private $postal = null;
    private $country = null;

    public function __construct() {}

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
        if ( isset( $legalName ) && isset( $tradeName ) && isset( $website ) && isset( $firstName ) && isset( $lastName ) && isset( $phoneNumber ) && isset( $faxNumber ) && isset( $address1 ) && isset( $address2 ) && isset( $city ) && isset( $province ) && isset( $postal ) && isset( $country ) )
            return $legalName.$tradeName.$website.$firstName.$lastName.$phoneNumber.$faxNumber.$address1.$address2.$city.$province.$postal.$country;

    }
    function isBlank() {
        return(
            !( ( $this->$firstName != null && strlen( $this->$firstName ) > 0 )
                || ( $this->$lastName != null && strlen( $this->$lastName ) > 0 )
                || ( $this->$legalName != null && strlen( $this->$legalName ) > 0 )
                || ( $this->$tradeName != null && strlen( $this->$tradeName ) > 0 )
                || ( $this->$address1 != null && strlen( $this->$address1 ) > 0 )
                || ( $this->$address2 != null && strlen( $this->$address2 ) > 0 )
                || ( $this->$city != null && strlen( $this->$city ) > 0 )
                || ( $this->$province != null && strlen( $this->$province ) > 0 )
                || ( $this->$postal != null && strlen( $this->$postal ) > 0 )
                || ( $this->$country != null && strlen( $this->$country ) > 0 )
                || ( $this->$website != null && strlen( $this->$website ) > 0 )
                || ( $this->$phoneNumber != null && strlen( $this->$phoneNumber ) > 0 )
                || ( $this->$faxNumber != null && strlen( $this->$faxNumber ) > 0 )
            )
        );
    }
}
