<?php
include __DIR__.'/../lib/SALT.php';

/** An example of using the SALT Secure Storage API to store then use a stored Credit Card */

use \SALT\Merchant;
use \SALT\HttpsCreditCardService;
use \SALT\CreditCard;
use \SALT\PaymentProfile;

// connection parameters to the gateway
$url = 'https://test.salt.com/gateway/creditcard/processor.do';
//$merchant = new Merchant ('Your Merchant ID', 'Your API Token');
$merchant = new Merchant ( VALID_MERCHANT_ID, VALID_API_TOKEN );
$service = new HttpsCreditCardService( $merchant, $url );

// credit card info from customer - to be stored
$creditCard = new CreditCard( '4242424242424242', '1010', null, '123 Street', 'A1B23C' );

// payment profile to be stored (just using the card component in this example)
$paymentProfile = new PaymentProfile( $creditCard, null );

// store data under the token 'my-token-001'
//$storageToken = 'my-token-'.date('Ymd');
$storageToken = uniqid();
$storageReceipt = $service->addToStorage( $storageToken, $paymentProfile );



if ( $storageReceipt->approved ) {
    echo "Credit card storage approved.\n";
    $purchaseOrderId = uniqid();
    echo "Creating Single purchase with Order ID $purchaseOrderId\n";
    $singlePurchaseReceipt = $service->singlePurchase( $purchaseOrderId, $storageToken, '100', null );

    // optional array dump of response params
    // print_r($receipt->params);

    if ( $singlePurchaseReceipt->approved ) {
        //Store the transaction id.
        echo "Single Purchase Receipt approved\n";
    } else {
        echo "Single purchase receipt not approved\n";
    }
} else {
    echo "Credit card storage not approved.\n";
}



//Update the credit card stored
$updatedCreditCard = new CreditCard ( '4012888888881881', '1010', null, '1 Market St.', '94105' );
$paymentProfile->creditCard = $updatedCreditCard;

$updatedStorageReceipt = $service->updateStorage($storageToken, $paymentProfile);


if ( $updatedStorageReceipt->approved ) {
    echo "Updated credit card storage approved.\n";
} else {
    echo "Updated credit card storage not approved.\n";
}

//Query the credit card stored

$queryStorageReceipt = $service->queryStorage($storageToken);
if ( $queryStorageReceipt->approved ) {
    echo "Secure storage query successful.\n";
    echo "Response: \n";
    print_r($queryStorageReceipt->params);
} else {
    echo "Secure storage query failed.\n";
}
