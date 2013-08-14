<?php

// SALT Client API for PHP >= 4.3

// constants

// error codes
define('REQ_MALFORMED_URL',-1);
define('REQ_POST_ERROR',-2);
define('REQ_RESPONSE_ERROR',-4);
define('REQ_CONNECTION_FAILED',-5);
define('REQ_INVALID_REQUEST',-6);

// market segment
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

//AdvancedRiskProfile
define('GENDER_MALE','M');
define('GENDER_FEMALE','F');

//Transaction source
define('TRANSACTION_SOURCE_IVR','IVR');
define('TRANSACTION_SOURCE_CRM','CRM');
define('TRANSACTION_SOURCE_WSC','WSC');
define('TRANSACTION_SOURCE_AP','AP');
define('TRANSACTION_SOURCE_AGENT','AGENT');

//Shipping type
define('SHIPPING_TYPE_SAME_DAY','SD');
define('SHIPPING_TYPE_NEXT_DAY','ND');
define('SHIPPING_TYPE_SECOND_DAY','2D');
define('SHIPPING_TYPE_STANDARD','ST');


// classes

class ApprovalInfo {
	var $authorizedAmount = 0;
	var $approvalCode = null;
	var $traceNumber = null;
	var $referenceNumber = null;
	// constructor
	function ApprovalInfo($authorizedAmount, $approvalCode, $traceNumber, $referenceNumber) {
		$this->authorizedAmount = $authorizedAmount;
		$this->approvalCode = $approvalCode;
		$this->traceNumber = $traceNumber;
		$this->referenceNumber = $referenceNumber;
	}

	function getAuthorizedAmount() {
		return $this->authorizedAmount;
	}
	function getApprovalCode() {
		return $this->approvalCode;
	}
	function getTraceNumber() {
		return $this->traceNumber;
	}
	function getReferenceNumber() {
		return $this->referenceNumber;
	}
} // ApprovalInfo

class AvsResponse {
	var $avsResponseCode;
	var $streetMatched;
	var $zipMatched;
	var $zipType;
	var $avsErrorCode;
	var $avsErrorMessage;
	// constructor
	function AvsResponse($avsResponseCode,$streetMatched, $zipMatched, $zipType,  $avsErrorCode, $avsErrorMessage) {
		$this->avsResponseCode = $avsResponseCode;
		$this->streetMatched = $streetMatched;
		$this->zipMatched = $zipMatched;
		$this->zipType = $zipType;
		$this->avsErrorCode = $avsErrorCode;
		$this->avsErrorMessage = $avsErrorMessage;
	}
	function getAvsErrorCode() {
		return $this->avsErrorCode;
	}
	function getAvsErrorMessage() {
		return $this->avsErrorMessage;
	}
	function getAvsResponseCode() {
		return $this->avsResponseCode;
	}
	function getZipType() {
		return $this->zipType;
	}
	function isAvsPerformed(){
		if ($this->avsErrorMessage == null && $this->avsErrorCode ==null){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function isStreetFormatValid(){
		if ($this->streetMatched!=null){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function isStreetFormatValidAndMatched(){
		if ($this->isStreetFormatValid() == TRUE && $this->streetMatched == TRUE){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function isZipFormatValid(){
		if($this->zipMatched!=null){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function isZipFormatValidAndMatched(){
		if($this->isZipFormatValid() == TRUE && $this->zipMatched == TRUE){
			return TRUE;
		}else{
			return FALSE;
		}
	}
} // AvsResponse


class AvsRequest{
	var $code = null;

	function AvsRequest($code){
		$this->code = $code;
	}
}//AvsRequest

class CreditCard {
	var $creditCardNumber;
	var $expiryDate;
	var $cvv2;
	var $street;
	var $zip;
	var $secureCode;
	var $cardHolderName;
	// constructor
	function CreditCard($creditCardNumber, $expiryDate, $cvv2 = null,
	$street = null, $zip = null, $secureCode = null, $magneticData = null, $cardHolderName = null) {
		$this->creditCardNumber = $creditCardNumber;
		$this->expiryDate = $expiryDate;
		//$this->magneticData = $magneticData;
		$this->cvv2 = $cvv2;
		$this->street = $street;
		$this->zip = $zip;
		$this->secureCode = $secureCode;
		$this->cardHolderName = $cardHolderName;
	}
} // CreditCard

class CreditCardReceipt {
	var $params = null;
	var $approved = false;
	var $transactionId = null;
	var $orderId = null;
	var $processedDateTime = null;	// as a string
	var $processedDateTimestamp = null;	// as an int (can apply your own formatting to this value)
	var $errorCode = null;
	var $errorMessage = null;
	var $debugMessage = null;
	var $approvalInfo = null;
	var $avsResponse = null;
	var $cvv2Response = null;
	var $response = null;
	var $periodicPurchaseInfo = null;
	var $fraudScore = null;
	var $fraudDecision = null;

	// constructor parses response from gateway into this object
	function CreditCardReceipt($response = null) {
		if ($response == null) {
			return;
		}

		// parse response into param associative array

		$this->response = $response;
		$lines = explode("\n", $this->response);
		$this->params = array();

		$this->params["ERROR_CODE"] = null;
		$this->params["ERROR_MESSAGE"] = null;
		$this->params["DEBUG_MESSAGE"] = null;
		$this->params["AVS_RESPONSE_AVAILABLE"] = null;
		$this->params["CVV2_RESPONSE_AVAILABLE"] = null;
		$this->params["AUTHORIZED_AMOUNT"] = null;
		$this->params["APPROVAL_CODE"] = null;
		$this->params["TRACE_NUMBER"] = null;
		$this->params["REFERENCE_NUMBER"] = null;
        $this->params["PERIODIC_TRANSACTION_ID"] = null;
        $this->params["TRANSACTION_ID"]= null;
        $this->params["ORDER_ID"] = null;
        $this->params["PROCESSED_DATE"] = null;
        $this->params["PROCESSED_TIME"] = null;
        $this->params["APPROVED"] = null;


		$size = count($lines);
		for ($i = 0; $i < $size; $i++)
		{
			list($paramKey, $paramValue) = explode("=", $lines[$i]);
			$this->params[$paramKey] = $paramValue;
		}

		// parse the param into data class objects

		$this->approved = $this->params["APPROVED"] == 'true';

		$this->transactionId = $this->params["TRANSACTION_ID"];
		$this->orderId = $this->params["ORDER_ID"];
		// returned date time is in yymmddhhiiss format
		$processedDate = $this->params["PROCESSED_DATE"];
		$processedTime = $this->params["PROCESSED_TIME"];

		if ($processedDate != null && $processedTime != null) {
			$year = substr($processedDate, 0, 2);
			$month = substr($processedDate, 2, 2);
			$day = substr($processedDate, 4, 2);
			$hour = substr($processedTime, 0, 2);
			$minute = substr($processedTime, 2, 2);
			$second = substr($processedTime, 4, 2);
			$this->processedDateTimestamp = strtotime($year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':' . $second);
			$this->processedDateTime = date('r', $this->processedDateTimestamp);
		} else {
			$this->processedDateTime = null;
		}
		$this->errorCode = $this->params["ERROR_CODE"];
		$this->errorMessage = $this->params["ERROR_MESSAGE"];
		$this->debugMessage = $this->params["DEBUG_MESSAGE"];
		// parse the approval info
		if ($this->approved) {
			$this->approvalInfo = new ApprovalInfo(
			$this->params["AUTHORIZED_AMOUNT"],
			$this->params["APPROVAL_CODE"],
			$this->params["TRACE_NUMBER"],
			$this->params["REFERENCE_NUMBER"]);
		} else {
			$this->approvalInfo = null;
		}

		// parse the AVS response
		$avsResponseAvailable = $this->params["AVS_RESPONSE_AVAILABLE"];
		if ($avsResponseAvailable != null && $avsResponseAvailable) {
			$avsErrorCode = null;
			$avsErrorMessage = null;
			if (array_key_exists("AVS_ERROR_CODE", $this->params)){
				$avsErrorCode = $this->params["AVS_ERROR_CODE"];
			}
			if (array_key_exists("AVS_ERROR_MESSAGE", $this->params)){
                                $avsErrorMessage = $this->params["AVS_ERROR_MESSAGE"];
                        }
			$this->avsResponse = new AvsResponse(
			$this->params["AVS_RESPONSE_CODE"],
			$this->params["STREET_MATCHED"],
			$this->params["ZIP_MATCHED"],
			$this->params["ZIP_TYPE"],
			$avsErrorCode,
			$avsErrorMessage);
		} else {
			$this->avsResponse = null;
		}
		// parse the CVV2 response
		$cvv2ResponseAvailable = $this->params["CVV2_RESPONSE_AVAILABLE"];
		if ($cvv2ResponseAvailable != null && $cvv2ResponseAvailable) {
			$this->cvv2Response = new Cvv2Response(
			$this->params["CVV2_RESPONSE_CODE"],
			$this->params["CVV2_RESPONSE_MESSAGE"]);
		} else {
			$this->cvv2Response = null;
		}

			//Parse fraud related parameters
			$this->fraudScore = $this->params["FRAUD_SCORE"];
			$this->fraudDecision = $this->params["FRAUD_DECISION"];


		// parse periodic purchase info
		$periodicPurchaseId = $this->params["PERIODIC_TRANSACTION_ID"];
		if ($periodicPurchaseId != null) {
			$periodicPurchaseState = $this->params["PERIODIC_TRANSACTION_STATE"];
			$periodicNextPaymentDate = null;
			if (array_key_exists("PERIODIC_NEXT_PAYMENT_DATE", $this->params)){
				$periodicNextPaymentDate = $this->params["PERIODIC_NEXT_PAYMENT_DATE"];
			}
			$periodicLastPaymentId = null;
			if (array_key_exists("PERIODIC_LAST_PAYMENT_ID", $this->params)){
				$periodicLastPaymentId = $this->params["PERIODIC_LAST_PAYMENT_ID"];
			}
			$this->periodicPurchaseInfo = new PeriodicPurchaseInfo($periodicPurchaseId, $periodicPurchaseState, null, null, null, null, null, null, $periodicNextPaymentDate, $periodicLastPaymentId);
		} else {
			$this->periodicPurchaseInfo = null;
		}

	}

	// returns an error-only receipt (used when unable to connect to
	// gateway or process request).
	function errorOnlyReceipt($errorCode, $errorMessage = null, $debugMessage = null) {
		$theReceipt = new CreditCardReceipt();
		$theReceipt->errorCode = $errorCode;
		$theReceipt->errorMessage = $errorMessage;
		$theReceipt->debugMessage = $debugMessage;
		$theReceipt->processedDateTimestamp = time();
		$theReceipt->processedDateTime = date('r', $theReceipt->processedDateTimestamp);
		return $theReceipt;
	}

	function getApprovalInfo() {
		return $this->approvalInfo;
	}
	function getAvsResponse() {
		return $this->avsResponse;
	}
	function getCvv2Response() {
		return $this->cvv2Response;
	}
	function getDebugMessage() {
		return $this->debugMessage;
	}
	function getErrorCode() {
		return $this->errorCode;
	}
	function getErrorMessage() {
		return $this->errorMessage;
	}
	function getOrderId() {
		return $this->orderId;
	}
	function getProcessedDateTime() {
		return $this->processedDateTime;
	}
	function getTransactionId() {
		return $this->transactionId;
	}
	function isApproved() {
		return $this->approved;
	}
	function getPeriodicPurchaseInfo() {
		return $this->periodicPurchaseInfo;
	}
	function getFraudScore()
	{
		return $this->fraudScore;
	}

	function getFraudDecision()
	{
		return $this->fraudDecision;
	}

} // CreditCardReceipt

class Cvv2Response {
	var $code;
	var $message;
	function Cvv2Response($code, $message) {
		$this->code = $code;
		$this->message = $message;
	}
	function getCode() {
		return $this->code;
	}
	function getMessage() {
		return $this->message;
	}
}// Cvv2Response

class Cvv2Request{
	var $code = null;

	function Cvv2Request($code){
		$this->code = $code;
	}
}// Cvv2Request

class VerificationRequest {
	var $avsRequest = null;
	var $cvv2Request = null;
	var $advancedRiskProfile = null;

	// constructor
	function VerificationRequest($avsRequest, $cvv2Request,$advancedRiskProfile=null) {
		$this->avsRequest = $avsRequest;
		$this->cvv2Request = $cvv2Request;
		$this->advancedRiskProfile = $advancedRiskProfile;
	}

	function getAvsRequest(){
		return $this->avsRequest;
	}
	function getCvv2Request(){
		return $this->cvv2Request;
	}
	function isAvsEnabled(){
		if ($this->avsRequest !=null){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	function isCvv2Enabled(){
		if($this->cvv2Request !=null){
			return TRUE;
		}else{
			return FALSE;
		}
	}

} // VerificationRequest

class CustomerProfile {
	var $legalName = null;
	var $tradeName = null;
	var $website = null;
	var $firstName = null;
	var $lastName = null;
	var $phoneNumber = null;
	var $faxNumber = null;
	var $address1 = null;
	var $address2 = null;
	var $city = null;
	var $province = null;
	var $postal = null;
	var $country = null;

	function CustomerProfile ()
	{}

	function getLegalName() {
		return $this->legalName;
	}
	function getTradeName() {
		return $this->tradeName;
	}
	function getWebsite() {
		return $this->website;
	}
	function getFirstName() {
		return $this->firstName;
	}
	function getLastName() {
		return $this->lastName;
	}
	function getPhoneNumber() {
		return $this->phoneNumber;
	}
	function getFaxNumber() {
		return $this->faxNumber;
	}
	function getAddress1() {
		return $this->address1;
	}
	function getAddress2() {
		return $this->address2;
	}
	function getCity() {
		return $this->city;
	}
	function getProvince() {
		return $this->province;
	}
	function getPostal() {
		return $this->postal;
	}
	function getCountry() {
		return $this->country;
	}

	function setLegalName($legalName) {
		$this->legalName = $legalName;
	}
	function setTradeName($tradeName) {
		$this->tradeName = $tradeName;
	}
	function setWebsite($website) {
		$this->website = $website;
	}
	function setFirstName($firstName) {
		$this->firstName = $firstName;
	}
	function setLastName($lastName) {
		$this->lastName = $lastName;
	}
	function setPhoneNumber($phoneNumber) {
		$this->phoneNumber = $phoneNumber;
	}
	function setFaxNumber($faxNumber) {
		$this->faxNumber = $faxNumber;
	}
	function setAddress1($address1) {
		$this->address1 = $address1;
	}
	function setAddress2($address2) {
		$this->address2 = $address2;
	}
	function setCity($city) {
		$this->city = $city;
	}
	function setProvince($province) {
		$this->province = $province;
	}
	function setPostal($postal) {
		$this->postal = $postal;
	}
	function setCountry($country) {
		$this->country = $country;
	}

	function isBlank() {
		return(
		!(($this->$firstName != null && strlen($this->$firstName) > 0)
		|| ($this->$lastName != null && strlen($this->$lastName) > 0)
		|| ($this->$legalName != null && strlen($this->$legalName) > 0)
		|| ($this->$tradeName != null && strlen($this->$tradeName) > 0)
		|| ($this->$address1 != null && strlen($this->$address1) > 0)
		|| ($this->$address2 != null && strlen($this->$address2) > 0)
		|| ($this->$city != null && strlen($this->$city) > 0)
		|| ($this->$province != null && strlen($this->$province) > 0)
		|| ($this->$postal != null && strlen($this->$postal) > 0)
		|| ($this->$country != null && strlen($this->$country) > 0)
		|| ($this->$website != null && strlen($this->$website) > 0)
		|| ($this->$phoneNumber != null && strlen($this->$phoneNumber) > 0)
		|| ($this->$faxNumber != null && strlen($this->$faxNumber) > 0)
		)
		);
	}
}

class StorageReceipt
{
	var $params = null;
	var $approved = false;
	var $transactionId = null;
	var $orderId = null;
	var $processedDateTime = null;
	var $errorCode = null;
	var $errorMessage = null;
	var $debugMessage = null;
	var $response = null;
	var $paymentProfile= null;
	var $storageTokenId = null;

	function StorageReceipt ($response)
	{
		if ($response == null)
		{
			return;
		}

		$this->response = $response;
		$lines = explode("\n", $this->response);
		$this->params = array();

		$size = count($lines);
		for ($i = 0; $i < $size-1; $i++)
		{
			list($paramKey, $paramValue) = explode("=", $lines[$i]);
			$this->params[$paramKey] = $paramValue;
		}
		$this->approved = $this->params["APPROVED"] == 'true';
        $this->storageTokenId = $this->params["STORAGE_TOKEN_ID"];
        $this->errorCode = $this->params["ERROR_CODE"];
        $this->errorMessage = $this->params["ERROR_MESSAGE"];
        $this->debugMessage = $this->params["DEBUG_MESSAGE"];

		// make sure profile available
		$paymentProfileAvailable = $this->params["PAYMENT_PROFILE_AVAILABLE"];
		// parse the profile
		if ($paymentProfileAvailable != null && $paymentProfileAvailable)
		{
			// parse the CreditCard
			$creditCard = null;
			$creditCardAvailable = $this->params["CREDIT_CARD_AVAILABLE"];
			if ($creditCardAvailable != null && $creditCardAvailable) {
				$sanitized = $this->params["CREDIT_CARD_NUMBER"];
				$sanitized = str_replace("\\*","",$sanitized);
				$creditCard = new CreditCard($sanitized, $this->params["EXPIRY_DATE"]);
			}
			// parse the Customer Profile
			$profile = null;
			$customerProfileAvailable = $this->params["CUSTOMER_PROFILE_AVAILABLE"];
			if ($customerProfileAvailable != null && $customerProfileAvailable) {
				$profile = new CustomerProfile();
				$profile->setLegalName($this->params["CUSTOMER_PROFILE_LEGAL_NAME"]);
				$profile->setTradeName($this->params["CUSTOMER_PROFILE_TRADE_NAME"]);
				$profile->setWebsite($this->params["CUSTOMER_PROFILE_WEBSITE"]);
				$profile->setFirstName($this->params["CUSTOMER_PROFILE_FIRST_NAME"]);
				$profile->setLastName($this->params["CUSTOMER_PROFILE_LAST_NAME"]);
				$profile->setPhoneNumber($this->params["CUSTOMER_PROFILE_PHONE_NUMBER"]);
				$profile->setFaxNumber($this->params["CUSTOMER_PROFILE_FAX_NUMBER"]);
				$profile->setAddress1($this->params["CUSTOMER_PROFILE_ADDRESS1"]);
				$profile->setAddress2($this->params["CUSTOMER_PROFILE_ADDRESS2"]);
				$profile->setCity($this->params["CUSTOMER_PROFILE_CITY"]);
				$profile->setProvince($this->params["CUSTOMER_PROFILE_PROVINCE"]);
				$profile->setPostal($this->params["CUSTOMER_PROFILE_POSTAL"]);
				$profile->setCountry($this->params["CUSTOMER_PROFILE_COUNTRY"]);
			}
			$this->paymentProfile = new PaymentProfile($creditCard, $profile);
		}

		else {
			$this->paymentProfile = null;
		}

	}

	function getPaymentProfile() {
		return $this->paymentProfile;
	}
	/**
	 * @return storage token ID
	 */
	function getStorageTokenId() {
		return $this->storageTokenId;
	}

	function getDebugMessage() {
		return $this->debugMessage;
	}
	function getErrorCode() {
		return $this->errorCode;
	}
	function getErrorMessage() {
		return $this->errorMessage;
	}
	function getOrderId() {
		return $this->orderId;
	}
	function getProcessedDateTime() {
		return $this->processedDateTime;
	}
	function getTransactionId() {
		return $this->transactionId;
	}
	function isApproved() {
		return $this->approved;
	}

	function errorOnlyReceipt($errorCode, $errorMessage = null, $debugMessage = null)
	{
		$theReceipt = new StorageReceipt();
		$theReceipt->errorCode = $errorCode;
		$theReceipt->errorMessage = $errorMessage;
		$theReceipt->debugMessage = $debugMessage;
		$theReceipt->processedDateTimestamp = time();
		$theReceipt->processedDateTime = date('r', $theReceipt->processedDateTimestamp);
		return $theReceipt;
	}
}

class Schedule
{
	var $scheduleType = null;
	var $intervalLength = null;

	function Schedule($type, $intervalLength) {
		$this->scheduleType = $type;
		$this->intervalLength = $intervalLength;
	}

	function getScheduleType() {
		return $this->scheduleType;
	}

	function getIntervalLength() {
		return $this->intervalLength;
	}
}

class PeriodicPurchaseInfo
{
	var $periodicTransactionId = null;
	var $lastPaymentId = null;
	var $state = null;
	var $schedule = null;
	var $perPaymentAmount = null;
	var $orderId = null;
	var $customerId = null;
	var $startDate = null;
	var $endDate = null;
	var $nextPaymentDate = null;

	function PeriodicPurchaseInfo($periodicTransactionId, $state, $schedule = null, $perPaymentAmount = null,
			$orderId = null, $customerId = null, $startDate = null, $endDate = null, $nextPaymentDate = null, $lastPaymentId = null) {
		$this->periodicTransactionId = $periodicTransactionId;
		$this->state = $state;
		$this->schedule = $schedule;
		$this->perPaymentAmount = $perPaymentAmount;
		$this->orderId = $orderId;
		$this->customerId = $customerId;
		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$this->nextPaymentDate = $nextPaymentDate;
		$this->lastPaymentId = $lastPaymentId;
	}

	function getPeriodicTransactionId() {
		return $this->periodicTransactionId;
	}
	function getLastPaymentId() {
		return $this->lastPaymentId;
	}
	function getState() {
		return $this->state;
	}
	function getSchedule() {
		return $this->schedule;
	}
	function getPerPaymentAmount() {
		return $this->perPaymentAmount;
	}
	function getOrderId() {
		return $this->orderId;
	}
	function getCustomerId() {
		return $this->customerId;
	}
	function getStartDate() {
		return $this->startDate;
	}
	function getEndDate() {
		return $this->endDate;
	}
	function getNextPaymentDate() {
		return $this->nextPaymentDate;
	}
}

class PaymentProfile
{
	var $creditCard = null;
 	var $customerProfile = null;

	function PaymentProfile($creditCard, $customerProfile) {
		$this->creditCard = $creditCard;
		$this->customerProfile = $customerProfile;
	}

	function getCreditCard() {
		return $this->creditCard;
	}

	function getCustomerProfile() {
		return $this->customerProfile;
	}

	function setCreditCard($newCreditCard) {
		$this->creditCard = $newCreditCard;
	}
	function setCustomerProfile($newCustomerProfile) {
		$this->customerProfile = $newCustomerProfile;
	}
}

class Merchant
{
	var $merchantId;
	var $apiToken;
	var $storeId;

	function Merchant ($merchantId, $apiToken, $storeId = null){
		$this->merchantId = $merchantId;
                $this->apiToken = $apiToken;
                $this->storeId = $storeId;
	}

	function getMerchantId(){
		return $this->merchantId;
	}

	function getApiToken(){
                return $this->apiToken;
        }

	function getStoreId(){
                return $this->storeId;
        }
}

class Customer {
	var $emailHash = null;
	var $customerName = null;
	var $gender = null;
	var $driverLicense = null;
	var $fraudSessionId = null;
	var $anid = null;
	var $uniqueId = null;
	var $ip = null;
	var $userAgent = null;

	function Customer($fraudSessionId,$emailHash,$customerName,$gender,$driverLicense,$anid,$uniqueId,$ip,$userAgent){
		$this->fraudSessionId = $fraudSessionId;
		$this->emailHash = $emailHash;
		$this->customerName = $customerName;
		$this->gender = $gender;
		$this->driverLicense = $driverLicense;
		$this->anid = $anid;
		$this->uniqueId = $uniqueId;
		$this->ip = $ip;
		$this->userAgent = $userAgent;
	}

}

class Merchandise{
	var $billingPhone = null;
	var $shippingPhone = null;
	var $shippingName = null;
	var $shippingType = null;
	var $transactionSource = null;

	function Merchandise($billingPhone,$shippingPhone,$shippingName,$shippingType,$transactionSource)
	{
		$this->$billingPhone = $billingPhone;
		$this->$shippingPhone = $shippingPhone;
		$this->$shippingName = $shippingName;
		$this->$shippingType = $shippingType;
		$this->$transactionSource = $transactionSource;
	}
}

class Address {
	var $ddress = null;
	var $city = null;
	var $province = null;
	var $postal = null;
	var $country = null;

	function Address($address,$city, $province, $postal, $country){
		$this->address = $address;
		$this->city = $city;
		$this->province = $province;
		$this->postal = $postal;
		$this->country = $country;
	}
}

class AdvancedRiskProfile {
	var $customer = null;
	var $billingAddress = null;
	var $merchandise = null;

	function AdvancedRiskProfile($customer,$billingAddress, $merchandise){
		$this->customer = $customer;
		$this->billingAddress = $billingAddress;
		$this->merchandise = $merchandise;
	}
}
?>
