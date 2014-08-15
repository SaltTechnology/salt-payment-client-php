<?php
class Merchandise {
    private $billingPhone = null;
    private $shippingPhone = null;
    private $shippingName = null;
    private $shippingType = null;
    private $transactionSource = null;

    public function __construct( $billingPhone, $shippingPhone, $shippingName, $shippingType, $transactionSource ) {
        $this->$billingPhone = $billingPhone;
        $this->$shippingPhone = $shippingPhone;
        $this->$shippingName = $shippingName;
        $this->$shippingType = $shippingType;
        $this->$transactionSource = $transactionSource;
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
        if ( isset( $billingPhone ) && isset( $shippingPhone ) && isset( $shippingName ) && isset( $shippingType ) && isset( $transactionSource ) )
            return $billingPhone.$shippingPhone.$shippingName.$shippingType.$transactionSource;
    }



}