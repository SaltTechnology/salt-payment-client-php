<?php
// constants

// Error codes
define('REQ_MALFORMED_URL',-1);
define('REQ_POST_ERROR',-2);
define('REQ_RESPONSE_ERROR',-4);
define('REQ_CONNECTION_FAILED',-5);
define('REQ_INVALID_REQUEST',-6);

// Market segment
define('MARKET_SEGMENT_INTERNET', 'I');
define('MARKET_SEGMENT_MOTO', 'M');
define('MARKET_SEGMENT_RETAIL', 'G');

// AVS request
define('AVS_VERIFY_STREET_AND_ZIP', 0);
define('AVS_VERIFY_ZIP_ONLY', 1);

// Cvv2 request
define('CVV2_NOT_SUBMITTED', 0);
define('CVV2_PRESENT', 1);
define('CVV2_PRESENT_BUT_ILLEGIBLE', 2);
define('CVV2_HAS_NO_CVV2', 9);


// PeriodicPurchaseInfo
define('MONTH', 0);
define('WEEK', 1);
define('DAY', 2);

define('NEW',0);
define('IN_PROGRESS',1);
define('COMPLETE',2);
define('ON_HOLD', 3);
define('CANCELLED',4);

define('DATE_FORMAT', 'yymmdd');

// AdvancedRiskProfile
define('GENDER_MALE','M');
define('GENDER_FEMALE','F');

// Transaction source
define('TRANSACTION_SOURCE_IVR','IVR');
define('TRANSACTION_SOURCE_CRM','CRM');
define('TRANSACTION_SOURCE_WSC','WSC');
define('TRANSACTION_SOURCE_AP','AP');
define('TRANSACTION_SOURCE_AGENT','AGENT');

// Shipping type
define('SHIPPING_TYPE_SAME_DAY','SD');
define('SHIPPING_TYPE_NEXT_DAY','ND');
define('SHIPPING_TYPE_SECOND_DAY','2D');
define('SHIPPING_TYPE_STANDARD','ST');

// Connection parameters to the gateway
define('GATEWAY_URL','https://test.salt.com/gateway/creditcard/processor.do');
define('FIRST_TEST_MERCHANT_ID','5333621');
define('FIRST_TEST_API_TOKEN','0af93aad017256865259cc7209878a1f');
define('SECOND_TEST_MERCHANT_ID','300');
define('SECOND_TEST_API_TOKEN','abc');


// Credit card info from customer - to be stored
define('FIRST_TEST_CREDIT_CARD','5555555555554444');
define('SECOND_TEST_CREDIT_CARD','4242424242424242');
define('CVV2','111');
define('EXPIRY_DATE','1010');
define('STREET','123 Street');
define('ZIP','A1B23C');

// Store data under the token 'my-token-001'
define('STORAGE_TOKEN','my-token-001');
define('STORAGE_APPROVED','Storage Approved:');

// Single purchase
define('ORDER_ID','stored-card-001');
define('AMOUNT','100');
define('SINGLE_PURCHASE_RESULTS','Single Purchase with stored card results:');

// Recurring purchase
define('SCHEDULE_WEEK','2');
define('RECURRING_ORDER_ID','recurring-001');
define('PER_PAYMENT_AMOUNT','1000');
define('START_DATE','131010');
define('END_DATE','131210');
define('PERIODIC_TRANSACTION_ID','Periodic Txn ID:');
define('DEBUG_MESSAGE','Periodic Txn ID:');
?>
