<?php
class CreditCard {
    private $creditCardNumber;
    private $expiryDate;
    private $cvv2;
    private $street;
    private $zip;
    private $secureCode;
    private $cardHolderName;
    // constructor
    public function __construct( $creditCardNumber, $expiryDate, $cvv2 = null,
        $street = null, $zip = null, $secureCode = null, $magneticData = null, $cardHolderName = null ) {
        $this->creditCardNumber = $creditCardNumber;
        $this->expiryDate = $expiryDate;
        //$this->magneticData = $magneticData;
        $this->cvv2 = $cvv2;
        $this->street = $street;
        $this->zip = $zip;
        $this->secureCode = $secureCode;
        $this->cardHolderName = $cardHolderName;
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

        if ( isset( $creditCardNumber ) && isset( $expiryDate ) && isset( $cvv2 ) && isset( $street )
            && isset( $zip ) && isset( $secureCode ) && isset( $cardHolderName ) ) {
            return $creditCardNumber . $expiryDate . $cvv2 . $street . $zip . $secureCode . $cardHolderName;
        }
    }

} // CreditCard
