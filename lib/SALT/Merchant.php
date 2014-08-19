<?php
namespace SALT;
class Merchant {
    private $merchantId;
    private $apiToken;
    private $storeId;

    public function __construct( $merchantId, $apiToken, $storeId = null ) {
        $this->merchantId = $merchantId;
        $this->apiToken = $apiToken;
        $this->storeId = $storeId;
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
        if ( isset( $merchantId ) && isset( $apiToken ) && isset( $storeId ) )
            return $merchantId.$apiToken.$storeId;
    }

}
