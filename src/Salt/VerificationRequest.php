<?php
namespace SALT;
class VerificationRequest {
    private $avsRequest = null;
    private $cvv2Request = null;
    private $advancedRiskProfile = null;

    // constructor
    public function __construct( $avsRequest, $cvv2Request, $advancedRiskProfile=null ) {
        $this->avsRequest = $avsRequest;
        $this->cvv2Request = $cvv2Request;
        $this->advancedRiskProfile = $advancedRiskProfile;
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


    function isAvsEnabled() {
        if ( $this->avsRequest !=null ) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    function isCvv2Enabled() {
        if ( $this->cvv2Request !=null ) {
            return TRUE;
        }else {
            return FALSE;
        }
    }

    function __toString() {

        if ( isset( $avsRequest ) && isset( $cvv2Request ) && isset( $advancedRiskProfile ) )
            return $avsRequest.$cvv2Request.$advancedRiskProfile;
    }

} // VerificationRequest