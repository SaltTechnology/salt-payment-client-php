<?php
include '../lib/HttpsCreditCardService.php';

/** Credit Card Single Purchase with SALT Payment API */

// connection parameters to the gateway
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
$merchant = new Merchant ('300', 'abc');
$service = new HttpsCreditCardService($merchant, $url);

// credit card info from customer
$creditCard = new CreditCard('4242424242424242', '1010', '111', '123 Street', 'A1B23C');

$vr = new VerificationRequest(AVS_VERIFY_STREET_AND_ZIP, CVV2_PRESENT);

// send request
$receipt = $service->singlePurchase('order-001', $creditCard, '100', $vr);

// array dump of response params, you can access each param individually as well
// (see DataClasses.php, class CreditCardReceipt)
print_r($receipt->params);
?>
