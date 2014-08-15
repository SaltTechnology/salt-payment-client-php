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

/** An example of how to create a Recurring Purchase using the SALT Client API */
class RecurringPurchaseTest extends PHPUnit_Framework_TestCase
 {
      /* Creates a Recurring Purchase using the SALT Client API
      and checks whether the receipt is valid */
      public function testReceiptIsValid()
      {
      // connection parameters to the Admeris CC gateway
      $url = GATEWAY_URL;
      $merchant = new Merchant (SECOND_TEST_MERCHANT_ID, SECOND_TEST_API_TOKEN);
      $service = new HttpsCreditCardService($merchant, $url);

      // credit card info from customer
      $creditCard = new CreditCard(SECOND_TEST_CREDIT_CARD, EXPIRY_DATE, CVV2, STREET, ZIP);

      // recur schedule (in this example, every 2 weeks)
      $schedule = new Schedule(WEEK, SCHEDULE_WEEK);

     // set a recurring purchase to run from 2013-10-10 until 2013-12-10
     // Note that date format is 'yymmdd'
     $receipt = $service->recurringPurchase(RECURRING_ORDER_ID, $creditCard, PER_PAYMENT_AMOUNT,     START_DATE, END_DATE, $schedule, null);

     // Show result (see DataClasses.php, class CreditCardReceipt for more fields)
     echo STORAGE_APPROVED . $receipt->approved;
     $this->assertTrue($receipt->approved != 'false');
     echo '<br/>';
     if(isset( $receipt->periodicPurchaseInfo->periodicTransactionId)) {
         echo PERIODIC_TRANSACTION_ID . $receipt->periodicPurchaseInfo->periodicTransactionId;
         echo '<br/>';
     }
          if(isset($receipt->debugMessage)) {
              echo DEBUG_MESSAGE . $receipt->debugMessage;
          }
     }
 }
?>
