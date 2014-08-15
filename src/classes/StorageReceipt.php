<?php
class StorageReceipt {
    private $params = null;
    private $approved = false;
    private $transactionId = null;
    private $orderId = null;
    private $processedDateTime = null;
    private $errorCode = null;
    private $errorMessage = null;
    private $debugMessage = null;
    private $response = null;
    private $paymentProfile= null;
    private $storageTokenId = null;

    public function __construct( $response ) {

        if ( $response == null ) {
            return;
        }




        $this->response = $response;
        $lines = explode( "\n", $this->response );
        $this->params = array();

        $size = count( $lines );
        for ( $i = 0; $i < $size-1; $i++ ) {
            list( $paramKey, $paramValue ) = explode( "=", $lines[$i] );
            $this->params[$paramKey] = $paramValue;
        }
        $this->approved = $this->params["APPROVED"] == 'true';
        if ( isset( $this->params["STORAGE_TOKEN_ID"] ) ) {

            $this->storageTokenId = $this->params["STORAGE_TOKEN_ID"];
        }
        else {
            throw new SaltError( UNDEFINED_STORAGE_TOKEN );
        }

        if ( isset( $this->params["ERROR_CODE"] ) ) {
            $this->errorCode = $this->params["ERROR_CODE"];
        }
        if ( isset( $this->params["ERROR_MESSAGE"] ) ) {
            $this->errorMessage = $this->params["ERROR_MESSAGE"];
        }
        if ( isset( $this->params["DEBUG_MESSAGE"] ) ) {
            $this->debugMessage = $this->params["DEBUG_MESSAGE"];
        }

        if ( isset( $this->params["PAYMENT_PROFILE_AVAILABLE"] ) ) {
            // make sure profile available
            $paymentProfileAvailable = $this->params["PAYMENT_PROFILE_AVAILABLE"];




            // parse the profile
            if ( $paymentProfileAvailable != null && $paymentProfileAvailable ) {
                // parse the CreditCard
                $creditCard = null;
                $creditCardAvailable = $this->params["CREDIT_CARD_AVAILABLE"];
                if ( $creditCardAvailable != null && $creditCardAvailable ) {
                    $sanitized = $this->params["CREDIT_CARD_NUMBER"];
                    $sanitized = str_replace( "\\*", "", $sanitized );
                    $creditCard = new CreditCard( $sanitized, $this->params["EXPIRY_DATE"] );
                }
                // parse the Customer Profile
                $profile = null;
                $customerProfileAvailable = $this->params["CUSTOMER_PROFILE_AVAILABLE"];
                if ( $customerProfileAvailable != null && $customerProfileAvailable ) {
                    $profile = new CustomerProfile();
                    if ( isset( $this->params["CUSTOMER_PROFILE_LEGAL_NAME"] ) ) {
                        $profile->setLegalName( $this->params["CUSTOMER_PROFILE_LEGAL_NAME"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_TRADE_NAME"] ) ) {
                        $profile->setTradeName( $this->params["CUSTOMER_PROFILE_TRADE_NAME"] );
                    }

                    if ( isset( $this->params["CUSTOMER_PROFILE_WEBSITE"] ) ) {

                        $profile->setWebsite( $this->params["CUSTOMER_PROFILE_WEBSITE"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_FIRST_NAME"] ) ) {
                        $profile->setFirstName( $this->params["CUSTOMER_PROFILE_FIRST_NAME"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_LAST_NAME"] ) ) {
                        $profile->setLastName( $this->params["CUSTOMER_PROFILE_LAST_NAME"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_PHONE_NUMBER"] ) ) {
                        $profile->setPhoneNumber( $this->params["CUSTOMER_PROFILE_PHONE_NUMBER"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_FAX_NUMBER"] ) ) {
                        $profile->setFaxNumber( $this->params["CUSTOMER_PROFILE_FAX_NUMBER"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_ADDRESS1"] ) ) {
                        $profile->setAddress1( $this->params["CUSTOMER_PROFILE_ADDRESS1"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_ADDRESS2"] ) ) {
                        $profile->setAddress2( $this->params["CUSTOMER_PROFILE_ADDRESS2"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_CITY"] ) ) {
                        $profile->setCity( $this->params["CUSTOMER_PROFILE_CITY"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_PROVINCE"] ) ) {
                        $profile->setProvince( $this->params["CUSTOMER_PROFILE_PROVINCE"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_POSTAL"] ) ) {
                        $profile->setPostal( $this->params["CUSTOMER_PROFILE_POSTAL"] );
                    }
                    if ( isset( $this->params["CUSTOMER_PROFILE_COUNTRY"] ) ) {
                        $profile->setCountry( $this->params["CUSTOMER_PROFILE_COUNTRY"] );
                    }
                }
                $this->paymentProfile = new PaymentProfile( $creditCard, $profile );
            }

            else {
                $this->paymentProfile = null;
            }

        }
        else {

            throw new SaltError( UNDEFINED_PAYMENT_PROFILE_AVAILABILITY );
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
        if ( isset( $params ) && isset( $approved ) && isset( $transactionId ) && isset( $orderId ) && isset( $processedDateTime ) && isset( $errorCode ) && isset( $errorMessage ) && isset( $debugMessage ) && isset( $response ) && isset( $paymentProfile ) && isset( $storageTokenId ) )
            return $params.$approved.$transactionId.$orderId.$processedDateTime.$errorCode.$errorMessage.$debugMessage.$response.$paymentProfile.$storageTokenId ;


    }

    function errorOnlyReceipt( $errorCode, $errorMessage = null, $debugMessage = null ) {
        $theReceipt = new StorageReceipt();
        $theReceipt->errorCode = $errorCode;
        $theReceipt->errorMessage = $errorMessage;
        $theReceipt->debugMessage = $debugMessage;
        $theReceipt->processedDateTimestamp = time();
        $theReceipt->processedDateTime = date( 'r', $theReceipt->processedDateTimestamp );
        return $theReceipt;
    }
}
