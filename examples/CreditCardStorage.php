<?php
include __DIR__.'/../lib/SALT.php';

/** An example of using the SALT Secure Storage API to store then use a stored Credit Card */

use \SALT\Merchant;
use \SALT\HttpsCreditCardService;
use \SALT\CreditCard;
use \SALT\PaymentProfile;

// connection parameters to the gateway
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
$merchant = new Merchant ('Your Merchant ID', 'Your API Token');
$service = new HttpsCreditCardService($merchant, $url);

// credit card info from customer - to be stored
$creditCard = new CreditCard('4242424242424242', '1010', null, '123 Street', 'A1B23C');

// payment profile to be stored (just using the card component in this example)
$paymentProfile = new PaymentProfile($creditCard, null);

// store data under the token 'my-token-001'
//$storageToken = 'my-token-'.date('Ymd');
$storageToken = uniqid();
$receipt = $service->addToStorage($storageToken, $paymentProfile);

// Approved?
echo 'Storage Approved: ' . $receipt->approved ? "true" : "false";

// if stored, now use in a purchase
if ($receipt->approved) {
    // send request
    //$receipt = $service->singlePurchase('stored-card-'.date('Ymd'), $storageToken, '100', null);
    $receipt = $service->singlePurchase(uniqid(), $storageToken, '100', null);
    // array dump of response params
    echo '\n';
    echo 'Single Purchase with stored card results: '; 
    print_r($receipt->params);
}

?>
