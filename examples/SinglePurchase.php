<?php
include __DIR__.'/../lib/SALT.php';

/** Credit Card Single Purchase with SALT Payment API */
use \SALT\Merchant;
use \SALT\HttpsCreditCardService;
use \SALT\CreditCard;
use \SALT\VerificationRequest;


// connection parameters to the gateway
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
//$merchant = new Merchant ('Your Merchant ID', 'Your API Token');
$merchant = new Merchant ( VALID_MERCHANT_ID, VALID_API_TOKEN );
$service = new HttpsCreditCardService( $merchant, $url );

// credit card info from customer
$creditCard = new CreditCard( '4242424242424242', '1010', '111', '123 Street', 'A1B23C' );

$vr = new VerificationRequest( SKIP_AVS_VALIDATE, SKIP_CVV_VALIDATE );
$orderId = uniqid();
        

// send request
$receipt = $service->singlePurchase( $orderId, $creditCard, '1000', $vr );

// array dump of response params, you can access each param individually as well
// (see DataClasses.php, class CreditCardReceipt)
print_r( $receipt->params );
?>
