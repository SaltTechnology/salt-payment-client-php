<?php
class PaymentProfile {
    private $creditCard = null;
    private $customerProfile = null;

    public function __construct( $creditCard, $customerProfile ) {
        $this->creditCard = $creditCard;
        $this->customerProfile = $customerProfile;
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
        if ( isset( $creditCard ) && isset( $customerProfile ) )
            return $creditCard.$customerProfile;
    }

}