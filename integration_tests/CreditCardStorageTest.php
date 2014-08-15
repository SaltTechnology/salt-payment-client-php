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

/** An example of using the SALT Secure Storage API to store then use a stored Credit Card */
class CreditCardStorageTest extends PHPUnit_Framework_TestCase
 {
      /* Stores and uses a stored Credit Card with ALT Secure Storage API
      and checks whether the receipt is valid*/
      public function testReceiptIsValid()
      {
      // connection parameters to the gateway
      $url = GATEWAY_URL;
      $merchant = new Merchant (FIRST_TEST_MERCHANT_ID, FIRST_TEST_API_TOKEN);
      $service = new HttpsCreditCardService($merchant, $url);

      // credit card info from customer - to be stored
      $creditCard = new CreditCard(FIRST_TEST_CREDIT_CARD, EXPIRY_DATE, null, STREET, ZIP);

      // payment profile to be stored (just using the card component in this example)
      $paymentProfile = new PaymentProfile($creditCard, null);

      // store data under the token 'my-token-001'
      $storageToken = STORAGE_TOKEN;

      $receipt = $service->addToStorage($storageToken,$paymentProfile);

      // Approved?
      echo STORAGE_APPROVED . $receipt->approved."<br/>";


      $this->assertTrue($receipt->approved != 'false');
      // if stored, now use in a purchase
      if ($receipt->approved != 'false') {

	  // send request
	  $receipt = $service->singlePurchase(ORDER_ID, $storageToken, AMOUNT, null);
      $this->assertTrue($receipt!= null);

      // array dump of response params
	  echo '<br/>';
	  echo SINGLE_PURCHASE_RESULTS;
      $this->assertTrue($receipt->params!= null);
	  print_r($receipt->params);
        }
      }
 }

?>
