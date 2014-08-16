<?php
namespace Salt;
class AdvancedRiskProfile {
    private $customer = null;
    private $billingAddress = null;
    private $merchandise = null;

    public function __construct( $customer, $billingAddress, $merchandise ) {
        $this->customer = $customer;
        $this->billingAddress = $billingAddress;
        $this->merchandise = $merchandise;
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
        if ( isset( $customer ) && isset( $billingAddress ) && isset( $merchandise ) )
            return $customer.$billingAddress.$merchandise;
    }

}