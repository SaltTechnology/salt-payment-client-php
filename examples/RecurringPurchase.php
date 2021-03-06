<?php
include __DIR__.'/../lib/SALT.php';

/** An example of how to create a Recurring Purchase using the SALT Client API */
use \SALT\Merchant;
use \SALT\HttpsCreditCardService;
use \SALT\CreditCard;
use \SALT\Schedule;

// connection parameters to the Salt CC gateway
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
//$merchant = new Merchant ('Your Merchant ID', 'Your API Token');
$merchant = new Merchant ( VALID_MERCHANT_ID, VALID_API_TOKEN );
$service = new HttpsCreditCardService( $merchant, $url );

// credit card info from customer
$creditCard = new CreditCard( '4242424242424242', '1010', '111', '123 Street', 'A1B23C' );

// recur schedule (in this example, every 2 weeks)
$schedule = new Schedule( WEEK, 2 );

// set a recurring purchase to run from 2013-10-10 until 2013-12-10
// Note that date format is 'yymmdd'
$receipt = $service->recurringPurchase( uniqid(), $creditCard, '1000', '131010', '131210', $schedule, null );

// Show result (see DataClasses.php, class CreditCardReceipt for more fields)
echo 'Approved: ' . $receipt->approved;
echo "\n";
echo 'Periodic Transaction ID: ' . $receipt->periodicPurchaseInfo->periodicTransactionId;
echo "\n";
echo 'Debug Message: ' . $receipt->debugMessage;
?>
