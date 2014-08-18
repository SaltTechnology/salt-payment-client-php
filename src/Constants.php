<?php
// constants

//Error Codes
const C001_TIMED_OUT = "Your request timed out before completing request, most likely because of heavy server load. Please retry the transaction later.";
const C002_SYSTEM_ERROR = "There was a system error processing your transaction. Please retry the transaction later.";
const C003_NETWORK_ERROR = "The transaction failed to process due to a network connection failure. If this error occurs, the merchant should query for the request to see whether or not it was successful.";
const C004_VALIDATION_ERROR = "There was a general error validating the merchant request input data. Please double-check the data being sent.";
const C005_DECLINED = "The transaction request was declined by the card processor. Please check the exipration date or the cvv2 value provided.";
const C100_INVALID_MERCHANT_CREDENTIALS = "Invalid merchant credentials: The provided merchant ID & API token pair do not exist.";
const C101_AMOUNT_OUT_OF_BOUNDS = "The specified transaction amount is invalid.";
const C102_INVALID_PURCHASE = "The specified purchase ID for this refund does not exist.";
const C103_INVALID_TRANSACTION = "The specified transaction for this void does not exist.";
const C104_PURCHASE_NOT_IN_REFUNDABLE_STATE = "The current purchase is not in a refundable state.";
const C105_PURCHASE_REFUND_AMOUNT_OVER_LIMIT = "This purchase cannot be refunded by the specified amount; this could be because it has exceeded the maximum amount that the purchase can be refunded.";
const C106_TRANSACTION_NOT_VOIDABLE = "The specified transaction is not in a voidable state.";
const C107_REQUEST_DENIED = "The merchant is not allowed to perform the specified request.";
const C108_ORDER_ID_ALREADY_EXIST = "The specified orderId already exists and is already associated with a different transaction.";
const C109_INVALID_TOTAL_NUMBER_INSTALLMENTS = "The total number of instalments of an instalment purchase is invalid.";
const C110_TRANSACTION_EXCEEDS_ACCOUNT_LIMITS = "Transaction cannot be processed because it has exceeds the maximum amount/count permissible to the account.";
const C111_TRANSACTION_DOES_NOT_EXIST = "Occurs when trying to perform an operation on a transaction but the specified transaction does not exist";
const C112_PERIODIC_PURCHASE_COMPLETE_OR_CANCELLED = "Occurs when trying to update a periodic/recurring purchase that has already been completed or cancelled";
const C200_INVALID_CREDIT_CARD_NUMBER = "The customer's credit card number is invalid (validation error).";
const C201_INVALID_CREDIT_CARD_EXPIRY_DATE = "The customer's credit card expiry date is invalid (validation error).";
const C202_INVALID_CREDIT_CARD_CVV2_FORMAT = "The customer's credit card CVV2 is not in the correct format (validation error).";
const C203_INVALID_ZIP_FORMAT = "The customer's zip/postal code is invalid (validation error).";
const C204_INVALID_STREET_FORMAT = "The customer's street is invalid (validation error).";
const C220_CVV2_VERIFICATION_FAILED = "Failed to verify CVV2.";
const C221_CVV2_VERIFICATION_NOT_SUPPORTED = "The issuer does not support CVV2 verification";
const C222_AVS_FAILED = "AVS Street or Zip verification failed";
const C223_AVS_NOT_SUPPORTED = "The issuer does not support AVS";
const C224_CREDIT_CARD_EXPIRED = "Expired Credit Card";
const C225_CARD_NOT_SUPPORTED = "Occurs when trying to process a credit card type that is not provisioned for (e.g. if AMEX is not setup for a Merchant account, an AMEX card will cause this error)";
const C226_CARD_LIMIT_EXCEEDED = "The transaction has exceeded the card credit limit.";
const C227_CARD_LOST_OR_STOLEN = "Credit card has been marked as lost or stolen by the issuer.";
const C300_STORAGE_TOKEN_ID_ALREADY_IN_USE = "The specified storageTokenId is already in use.";
const C301_STORAGE_RECORD_DOES_NOT_EXIST = "Storage Record with the specified storageTokenId does not exist";
const C302_NO_CREDIT_CARD_IN_STORAGE_RECORD = "Storage Record exists, but there is no credit card stored in it.";
const C400_DECLINED_FROM_FRAUD_PROVIDER = "Advanced Fraud Suite only. Declined due to failure of advanced fraud suite check.";
const C401_APPROVED_FROM_FRAUD_PROVIDER = "Advanced Fraud Suite only. Approved by fraud suite check. Applies to a fraud-suite-only check";
const C402_REVIEW_FROM_FRAUD_PROVIDER = "Advanced Fraud Suite only. A review status was returned by the fraud suite check. You should login to the Fraud Provider interface to review the transaction.";

//Util Constants
define('MARKET_SEGMENT_INTERNET', 'I');
define('MARKET_SEGMENT_MOTO', 'M');
define('MARKET_SEGMENT_RETAIL', 'G');




// Credit Card Receipt
define( 'UNDEFINED_STORAGE_TOKEN', 'Please provide the storage token' );
define( 'UNDEFINED_CREDIT_CARD_INFO', 'Please provide information whether credit card information is set' );
define( 'UNDEFINED_PAYMENT_PROFILE_AVAILABILITY', 'Please provide Information about payment profile availability' );
define( 'UNDEFINED_TRANSACTION_INFO', 'Please ensure all the necessary information about transaction is provided' );
define( 'UNDEFINED_FRAUD_SCORE', 'Please provide the fraud Score' );
define( 'UNDEFINED_FRAUD_DESICION', 'Please provide fraud decision' );
define( 'UNDEFINED_ORDER_ID', 'Please provide the corresponding order id' );
define( 'UNDEFINED_PROCESSED_DATE', 'Please provide the processed date' );
define( 'UNDEFINED_PROCESSED_TIME', 'Please provide the processed time' );
define( 'UNDEFINED_ERROR_CODE', 'The corresponding error code is not defined' );
define( 'UNDEFINED_ERROR_MESSAGE', 'The corresponding error message is not defined' );
define( 'UNDEFINED_DEBUG_MESSAGE', 'The corresponding debug message is not defined' );
define( 'UNDEFINED_AUTHORIZED_AMOUNT', 'The authorized amount is not provided' );
define( 'UNDEFINED_APPROVAL_CODE', 'The approval code is not available' );
define( 'UNDEFINED_TRACE_NUMBER', 'The trace number is not defined' );
define( 'UNDEFINED_REFERENCE_NUMBER', 'The reference number is not defined' );
define( 'UNDEFINED_AVS_RESPONSE', 'Information about avs response availability is not provided' );
define( 'UNDEFINED_AVS_RESPONSE_CODE', 'The avs response code is not defined' );
define( 'UNDEFINED_STREET_MATCHED', 'The information whether the street is matched is not provided' );
define( 'UNDEFINED_ZIP_MATCHED', 'The information whether the zip is matched is not provided' );
define( 'UNDEFINED_ZIP_TYPE', 'The information about the zip type is not provided' );
define( 'UNDEFINED_CVV2_RESPONSE_MESSAGE', 'The cvv2 response message is not available' );
define( 'UNDEFINED_CVV2_RESPONSE_CODE', 'The cvv2 response code is not available' );
?>
