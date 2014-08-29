<?php
namespace SALT;
class ApprovalInfo {
    private $authorizedAmount = 0;
    private $approvalCode = null;
    private $traceNumber = null;
    private $referenceNumber = null;
    // constructor
    public function __construct( $authorizedAmount, $approvalCode, $traceNumber, $referenceNumber ) {
        $this->authorizedAmount = $authorizedAmount;
        $this->approvalCode = $approvalCode;
        $this->traceNumber = $traceNumber;
        $this->referenceNumber = $referenceNumber;
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

        if ( isset( $authorizedAmount ) && isset( $approvalCode ) && isset( $traceNumber ) &&
            isset( $referenceNumber ) ) {
            return $authorizedAmount.$approvalCode.$traceNumber.$referenceNumber;
        }
    }


} // ApprovalInfo