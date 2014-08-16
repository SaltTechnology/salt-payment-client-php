<?php
namespace SALT;
class AvsResponse {
    private $avsResponseCode;
    private $streetMatched;
    private $zipMatched;
    private $zipType;
    private $avsErrorCode;
    private $avsErrorMessage;
    // constructor
    public function __construct( $avsResponseCode, $streetMatched, $zipMatched, $zipType,  $avsErrorCode, $avsErrorMessage ) {
        $this->avsResponseCode = $avsResponseCode;
        $this->streetMatched = $streetMatched;
        $this->zipMatched = $zipMatched;
        $this->zipType = $zipType;
        $this->avsErrorCode = $avsErrorCode;
        $this->avsErrorMessage = $avsErrorMessage;
    }

    function isAvsPerformed() {
        if ( $this->avsErrorMessage == null && $this->avsErrorCode ==null ) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    function isStreetFormatValid() {
        if ( $this->streetMatched!=null ) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    function isStreetFormatValidAndMatched() {
        if ( $this->isStreetFormatValid() == TRUE && $this->streetMatched == TRUE ) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    function isZipFormatValid() {
        if ( $this->zipMatched!=null ) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    function isZipFormatValidAndMatched() {
        if ( $this->isZipFormatValid() == TRUE && $this->zipMatched == TRUE ) {
            return TRUE;
        }else {
            return FALSE;
        }
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
        if ( isset( $avsResponseCode ) && isset( $streetMatched ) &&
            isset( $zipMatched ) && isset( $zipType ) && isset( $avsErrorCode ) && isset( $avsErrorMessage ) )
            return $avsResponseCode.$streetMatched.$zipMatched.$zipType.$avsErrorCode.$avsErrorMessage;
    }


} // AvsResponse
