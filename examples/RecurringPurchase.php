<?php
include '../lib/HttpsCreditCardService.php';

/** An example of how to create a Recurring Purchase using the SALT Client API */

// connection parameters to the Admeris CC gateway
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
$merchant = new Merchant ('yourMerchatId', 'yourApiToken');
$service = new HttpsCreditCardService($merchant, $url);

// credit card info from customer
$creditCard = new CreditCard('4242424242424242', '1010', '111', '123 Street', 'A1B23C');

// recur schedule (in this example, every 2 weeks)
$schedule = new Schedule(WEEK, 2);

// set a recurring purchase to run from 2013-10-10 until 2013-12-10
// Note that date format is 'yymmdd'
$receipt = $service->recurringPurchase('recurring-001', $creditCard, '1000', '131010', '131210', $schedule, null);

// Show result (see DataClasses.php, class CreditCardReceipt for more fields)
echo 'Approved: ' . $receipt->isApproved();
echo '<br/>';
echo 'Periodic Txn ID: ' . $receipt->getPeriodicPurchaseInfo()->getPeriodicTransactionId();
echo '<br/>';
echo 'Debug Message: ' . $receipt->getDebugMessage();
?>
