<?php

// SALT Client API for PHP >= 5.3

 // Report all PHP errors
 include '../src/Exceptions.php';

 // service class
 include '../src/HttpsCreditCardService.php';

 //predefined constants
 include 'IntegrationTestConstants.php';

 //predefined constants
 include '../src/DataClasses.php';

/** Credit Card Single Purchase with SALT Payment API */
 class SinglePurchaseTest extends PHPUnit_Framework_TestCase
 {
      /* implements the single purchase with SALT Payment API
      and checks whether the receipt is valid*/
      public function testReceiptIsValid()
      {
       // connection parameters to the gateway
       $url = GATEWAY_URL;
       $merchant = new Merchant (SECOND_TEST_MERCHANT_ID, SECOND_TEST_API_TOKEN);
       $service = new HttpsCreditCardService($merchant, $url);

       // credit card info from customer
       $creditCard = new CreditCard(SECOND_TEST_CREDIT_CARD, EXPIRY_DATE, CVV2, STREET,ZIP);

       $vr = new VerificationRequest(AVS_VERIFY_STREET_AND_ZIP, CVV2_PRESENT);  

       // send request
       $receipt = $service->singlePurchase(ORDER_ID, $creditCard,AMOUNT, $vr);
       $this->assertTrue($receipt->params!== null);
       $this->assertTrue($receipt->approved != 'false');
      }
 }
?>
