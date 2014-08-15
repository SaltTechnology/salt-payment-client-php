<?php
class Customer {
    private $emailHash = null;
    private $customerName = null;
    private $gender = null;
    private $driverLicense = null;
    private $fraudSessionId = null;
    private $anid = null;
    private $uniqueId = null;
    private $ip = null;
    private $userAgent = null;

    public function __construct( $fraudSessionId, $emailHash, $customerName, $gender, $driverLicense, $anid, $uniqueId, $ip, $userAgent ) {
        $this->fraudSessionId = $fraudSessionId;
        $this->emailHash = $emailHash;
        $this->customerName = $customerName;
        $this->gender = $gender;
        $this->driverLicense = $driverLicense;
        $this->anid = $anid;
        $this->uniqueId = $uniqueId;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
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
        if ( isset( $emailHash ) && isset( $customerName ) && isset( $gender ) && isset( $driverLicense ) && isset( $fraudSessionId ) && isset( $anid ) && isset( $uniqueId ) && isset( $ip ) && isset( $userAgent ) )
            return $emailHash.$customerName.$gender.$driverLicense.$fraudSessionId.$anid.$uniqueId.$ip.$userAgent;
    }


}