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

// Approved?

// if stored, now use in a purchase
$purchaseOrderId = uniqid();
$purchaseTransactionId = null;
$purchaseAmount = null;
if ( $storageReceipt->approved ) {
    echo "Credit card storage approved.\n";
    // send request
    //$receipt = $service->singlePurchase('stored-card-'.date('Ymd'), $storageToken, '100', null);

    echo "Creating Single purchase with Order ID $purchaseOrderId\n";
    $singlePurchaseReceipt = $service->singlePurchase( $purchaseOrderId, $storageToken, '100', null );

    // optional array dump of response params
    // print_r($receipt->params);

    if ( $singlePurchaseReceipt->approved ) {
        //Store the transaction id.
        echo "Single Purchase Receipt approved\n";
        $purchaseTransactionId = $singlePurchaseReceipt->transactionId;
        $purchaseAmount = $singlePurchaseReceipt->approvalInfo->authorizedAmount;
    } else {
        echo "Single purchase receipt not approved\n";
    }
} else {
    echo "Credit card storage not approved.\n";
}


//Purchase has been made, let's refund it.
if ( $purchaseTransactionId && $purchaseAmount ) {

    //First close the batch. Refunds cannot be processed until this is done.
    echo "Closing batch...\n";
    $batchReceipt = $service->closeBatch();

    //Once the batch is closed, you can issue a refund (We're doing the full amount here).
    if ( $batchReceipt->approved ) {
        echo "Batch closed. Issuing refund...\n";
        $refundReceipt = $service->refund( $purchaseTransactionId, $purchaseOrderId, null, $purchaseAmount );

        if ( $refundReceipt->approved ) {
            echo "Refund for order $purchaseOrderId approved!\n";
        }
    }


    // optional array dump of response params
    //print_r($receipt->params);


}
