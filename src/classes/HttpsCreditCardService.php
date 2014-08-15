<?php

// SALT Client API for PHP >= 5.3

class HttpsCreditCardService {
	public $merchant = null;
	public $marketSegment = '';
	public $url = '';

	// constructor
	function __construct() {
		$a = func_get_args();
		$i = func_num_args();
		if ( method_exists( $this, $f='HttpsCreditCardService'.$i ) ) {
			call_user_func_array( array( $this, $f ), $a );
		}
	}
	function HttpsCreditCardService3( $merchantId, $apiToken, $url, $marketSegment = MARKET_SEGMENT_INTERNET ) {
		$this->merchant = new Merchant( $merchantId, $apiToken );
		$this->marketSegment = $marketSegment;
		$this->url = $url;
	}

	function HttpsCreditCardService2( $merchant, $url, $marketSegment = MARKET_SEGMENT_INTERNET ) {
		$this->merchant = $merchant;
		$this->marketSegment = $marketSegment;
		$this->url = $url;
	}
	// public functions

	// send a refund
	function refund( $purchaseId, $purchaseOrderId, $refundOrderId, $amount ) {
		if ( $purchaseOrderId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "purchaseOrderId is required" );
		}
		// create the request string
		$req = array();

		$this->appendHeader( $req, "refund" );
		$this->appendTransactionId( $req, $purchaseId );
		$this->appendTransactionOrderId( $req, $purchaseOrderId );
		$this->appendOrderId( $req, $refundOrderId );
		$this->appendAmount( $req, $amount );
		return $this->send( $req, "creditcard" );
	}

	// single purchase
	function singlePurchase( $orderId,
		$creditCardSpecifier, $amount, $verificationRequest ) {



		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditcard or storageTokenId is required", null );
		}

		if ( $orderId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "orderId is required", null );
		}

		// create the request
		$req = array();

		$this->appendHeader( $req, "singlePurchase" );
		$this->appendOrderId( $req, $orderId );
		if ( is_string( $creditCardSpecifier ) ) {
			$this->appendStorageTokenId( $req, $creditCardSpecifier );
		}
		else {
			$this->appendCreditCard( $req, $creditCardSpecifier );
		}
		$this->appendAmount( $req, $amount );
		$this->appendVerificationRequest( $req, $verificationRequest );
		//return "got this far";
		return $this->send( $req, "creditcard" );
	}


	// single purchase
	function singlePurchase2( $orderId,
		$creditCardSpecifier, $amount, $verificationRequest, $secureTokenId ) {
		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditcard is required", null );
		}

		if ( $orderId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "orderId is required", null );
		}

		// create the request
		$req = array();

		$this->appendHeader( $req, "singlePurchase" );
		$this->appendOrderId( $req, $orderId );
		if ( is_string( $creditCardSpecifier ) ) {
			$this->appendStorageTokenId( $req, $creditCardSpecifier );
		}
		else {
			$this->appendCreditCard( $req, $creditCardSpecifier );
			$this->appendStorageFlag( $req, "true" );
			$this->appendStorageTokenId( $req, $secureTokenId );
		}
		$this->appendAmount( $req, $amount );
		$this->appendVerificationRequest( $req, $verificationRequest );
		//return "got this far";
		return $this->send( $req, "creditcard" );
	}

	//CHECK
	function installmentPurchase( $orderId, $creditCard, $preinstallmentamount,
		$startDate, $totalNumberInstallments , $verificationRequest ) {
		if ( $orderId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "orderId is required", null );
		}
		if ( $creditCard == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditCard is required", null );
		}
		$req = array();

		$this->appendHeader( $req, "installmentPurchase" );
		$this->appendOrderId( $req, $orderId );
		$this->appendCreditCard( $req, $creditCard );
		$this->appendAmount( $req, $preinstallmentamount );
		$this->appendStartDate( $req, $startDate );
		$this->appendTotalNumberInstallments( $req, $totalNumberInstallments );
		$this->appendVerificationRequest( $req, $verificationRequest );

		return $this->send( $req, "creditcard" );
	}

	//CHECK
	function recurringPurchase( $orderId,
		$creditCardSpecifier, $perPaymentAmount, $startDate,
		$endDate, $schedule, $verificationRequest ) {
		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditcard or storageTokenId is required", null );
		}

		if ( $orderId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "orderId is required", null );
		}


		$periodicPurchaseInfo = new PeriodicPurchaseInfo( null, null, $schedule, $perPaymentAmount,
			$orderId, null, $startDate, $endDate, null, null );

		return $this->recurringPurchaseHelper( $periodicPurchaseInfo, $creditCardSpecifier, $verificationRequest );
	}

	function recurringPurchase2( $periodicPurchaseInfo, $creditCardSpecifier,
		$verificationRequest ) {
		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditcard or storageTokenId is required", null );
		}
		if ( $periodicPurchaseInfo->orderId==null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "orderId is required", null );
		}
		return $this->recurringPurchaseHelper( $periodicPurchaseInfo, $creditCardSpecifier, $verificationRequest );

	}

	function recurringPurchaseHelper( $periodicPurchaseInfo, $creditCardSpecifier, $verificationRequest ) {
		$req = array();
		$this->appendHeader( $req, "recurringPurchase" );
		$this->appendOperationType( $req, "create" );
		$this->appendOrderId( $req, $periodicPurchaseInfo->orderId );
		$this->appendAmount( $req, $periodicPurchaseInfo->perPaymentAmount );
		$this->appendStartDate( $req, $periodicPurchaseInfo->startDate );
		$this->appendEndDate( $req, $periodicPurchaseInfo->endDate );
		$this->appendPeriodicPurchaseSchedule( $req, $periodicPurchaseInfo->schedule );
		$this->appendVerificationRequest( $req, $verificationRequest );
		if ( is_string( $creditCardSpecifier ) ) {
			$this->appendStorageTokenId( $req, $creditCardSpecifier );
			return $this -> send( $req, "storage" );
		}
		else {
			$this->appendCreditCard( $req, $creditCardSpecifier );
			return $this -> send( $req, "creditcard" );
		}

	}

	//CHECK
	function holdRecurringPurchase( $recurringPurchaseId ) {
		return $this->updateRecurringPurchaseHelper(
			new PeriodicPurchaseInfo( $recurringPurchaseId, ON_HOLD, null, null, null, null, null, null, null, null ),
			null, null );
	}

	function resumeRecurringPurchase( $recurringPurchaseId ) {
		return $this->updateRecurringPurchaseHelper(
			new PeriodicPurchaseInfo( $recurringPurchaseId, IN_PROGRESS, null, null, null, null, null, null, null, null ),
			null, null );
	}

	function cancelRecurringPurchase( $recurringPurchaseId ) {
		return $this->updateRecurringPurchaseHelper(
			new PeriodicPurchaseInfo( $recurringPurchaseId, CANCELLED, null, null, null, null, null, null, null, null ),
			null, null );
	}

	function queryRecurringPurchase( $recurringPurchaseId ) {
		if ( $recurringPurchaseId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "recurringPurchaseId is required", null );
		}
		$req = array();
		$this->appendHeader( $req, "recurringPurchase" );
		$this->appendOperationType( $req, "query" );
		$this->appendTransactionId( $req, $recurringPurchaseId );

		return $this->send( $req, "creditcard" );
	}
	function updateRecurringPurchase(
		$recurringPurchaseId, $creditCardSpecifier,
		$perPaymentAmount, $verificationRequest, $state ) {
		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditcard or storageTokenId is required", null );
		}

		if ( $recurringPurchaseId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "recurringPurchaseId is required", null );
		}

		$periodicPurchaseInfo = new PeriodicPurchaseInfo ( $recurringPurchaseId, $state, $perPaymentAmount, null, null, null, null, null, null, null );

		return updateRecurringPurchaseHelper( $periodicPurchaseInfo, $creditCardSpecifier, $verificationRequest );
	}

	function updateRecurringPurchase2(
		$periodicPurchaseInfo, $creditCardSpecifier, $verificationRequest ) {
		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditcard or storageTokenId is required", null );
		}
		if ( $periodicPurchaseInfo->periodicTransactionId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "recurringPurchaseId is required", null );
		}
		return $this->updateRecurringPurchaseHelper( $periodicPurchaseInfo, $creditCardSpecifier, $verificationRequest );
	}

	function updateRecurringPurchaseHelper( $periodicPurchaseInfo, $creditCardSpecifier, $verificationRequest ) {
		$req = array();
		$this->appendHeader( $req, "recurringPurchase" );
		$this->appendOperationType( $req, "update" );
		$this->appendTransactionId( $req, $periodicPurchaseInfo->periodicTransactionId );
		if ( is_string( $creditCardSpecifier ) ) {
			$this->appendStorageTokenId( $req, $creditCardSpecifier );
		}
		else {
			$this->appendCreditCard( $req, $creditCardSpecifier );
		}
		if ( $verificationRequest != null ) {
			$this->appendVerificationRequest( $req, $verificationRequest );
		}
		$this->appendPeriodicPurchaseInfo( $req, $periodicPurchaseInfo );
		return $this->send( $req, "creditcard" );
	}

	// verify-only
	function verifyCreditCard( $creditCardSpecifier, $verificationRequest ) {
		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "storageTokenId is required", null );
		}
		if ( $verificationRequest == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "verificationRequest is required", null );
		}
		// create the request string
		$req = array();

		$this->appendHeader( $req, "verifyCreditCard" );
		if ( is_string( $creditCardSpecifier ) ) {
			$this->appendStorageTokenId( $req, $creditCardSpecifier );
		}
		else {
			$this->appendCreditCard( $req, $creditCardSpecifier );
		}
		$this->appendVerificationRequest( $req, $verificationRequest );

		return $this->send( $req, "creditcard" );
	}


	function verifyCreditCard2( $creditCardSpecifier, $verificationRequest, $secureTokenId ) {
		if ( $creditCardSpecifier == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "creditcard is required", null );
		}
		if ( $verificationRequest == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "verificationRequest is required", null );
		}
		// create the request string
		$req = array();

		$this->appendHeader( $req, "verifyCreditCard" );
		if ( is_string( $creditCardSpecifier ) ) {
			$this->appendStorageTokenId( $req, $creditCardSpecifier );
		}
		else {
			$this->appendCreditCard( $req, $creditCardSpecifier );
			$this->appendStorageFlag( $req, "true" );
			$this->appendStorageTokenId( $req, $secureTokenId );
		}
		$this->appendVerificationRequest( $req, $verificationRequest );

		return $this->send( $req, "creditcard" );
	}


	// void
	function voidTransaction( $transactionId, $transactionOrderId ) {
		if ( $transactionOrderId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "transactionOrderId is required", null );
		}
		// create the request string
		$req = array();

		$this->appendHeader( $req, "void" );
		$this->appendTransactionId( $req, $transactionId );
		$this->appendTransactionOrderId( $req, $transactionOrderId );

		return $this->send( $req, "creditcard" );
	}
	// verify transaction
	function verifyTransaction( $transactionId, $transactionOrderId = null ) {
		if ( $transactionOrderId == null && $transactionId == null ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "at least one of transactionId or transactionOrderId is required", null );
		}
		// create the request string
		$req = array();

		$this->appendHeader( $req, "verifyTransaction" );
		$this->appendTransactionId( $req, $transactionId );
		$this->appendTransactionOrderId( $req, $transactionOrderId );

		return $this->send( $req, "creditcard" );
	}

	function addToStorage( $storageTokenId, $paymentProfile ) {

		if ( $paymentProfile == null ) {
			return StorageReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "payment profile is required", null );
		}
		// create the request string
		$req = array();

		$this->appendHeader( $req, "secureStorage" );
		$this->appendOperationType( $req, "create" );
		$this->appendStorageTokenId( $req, $storageTokenId );
		$this->appendPaymentProfile( $req, $paymentProfile );

		return $this->send( $req, "storage" );
	}

	function deleteFromStorage( $storageTokenId ) {
		if ( $storageTokenId == null ) {
			return StorageReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "storageTokenId is required", null );
		}

		$req = array();
		$this->appendHeader( $req, "secureStorage" );
		$this->appendOperationType( $req, "delete" );
		$this->appendStorageTokenId( $req, $storageTokenId );

		return $this->send( $req, "storage" );
	}

	function queryStorage( $storageTokenId ) {
		if ( $storageTokenId == null ) {
			return StorageReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "storageTokenId is required", null );
		}

		$req = array();
		$this->appendHeader( $req, "secureStorage" );
		$this->appendOperationType( $req, "query" );
		$this->appendStorageTokenId( $req, $storageTokenId );

		return $this->send( $req, "storage" );
	}

	function updateStorage( $storageTokenId, $paymentProfile ) {
		if ( $storageTokenId == null ) {
			return StorageReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "storageTokenId is required", null );
		}

		if ( $paymentProfile == null ) {
			return StorageReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, "payment profile is required", null );
		}

		$req = array();
		$this->appendHeader( $req, "secureStorage" );
		$this->appendOperationType( $req, "update" );
		$this->appendStorageTokenId( $req, $storageTokenId );
		$this->appendPaymentProfile( $req, $paymentProfile );

		return $this->send( $req, "storage" );
	}

	// internal functions

	function appendAmount( &$req, $amount ) {
		$this->appendParam( $req, "amount", $amount );
	}
	function appendApiToken( &$req, $apiToken ) {
		$this->appendParam( $req, "apiToken", $apiToken );
	}
	function appendCreditCard( &$req, $creditCard ) {
		if ( $creditCard != null ) {
			$this->appendParam( $req, "creditCardNumber",
				$creditCard->creditCardNumber );
			$this->appendParam( $req, "expiryDate", $creditCard->expiryDate );
			//$this->appendParam($req, "magneticData", $creditCard->magneticData());
			$this->appendParam( $req, "cvv2", $creditCard->cvv2 );
			$this->appendParam( $req, "street", $creditCard->street );
			$this->appendParam( $req, "zip", $creditCard->zip );
			$this->appendParam( $req, "secureCode", $creditCard->secureCode );
			$this->appendParam( $req, "cardHolderName", $creditCard->cardHolderName );
		}
	}

	function appendHeader( &$req, $requestCode ) {
		$this->appendParam( $req, "requestCode", $requestCode );
		$this->appendMerchantId( $req, $this->merchant->merchantId );
		$this->appendApiToken( $req, $this->merchant->apiToken );
		$this->appendParam( $req, "marketSegmentCode", $this->marketSegment );
	}

	function appendOperationType( &$req, $type ) {
		if ( $type != null ) {
			$this->appendParam( $req, "operationCode", $type );
		}
	}

	function appendPeriodicPurchaseState( &$req, $state ) {
		if ( $state != null ) {
			$this->appendParam( $req, "periodicPurchaseStateCode", $state );
		}
	}

	function appendPeriodicPurchaseSchedule( &$req, $schedule ) {
		if ( $schedule != null ) {
			$this->appendParam( $req, "periodicPurchaseScheduleTypeCode", $schedule->scheduleType );
			$this->appendParam( $req, "periodicPurchaseIntervalLength", $schedule->intervalLength );
		}
	}

	function appendPeriodicPurchaseInfo( &$req, $periodicPurchaseInfo ) {
		if ( $periodicPurchaseInfo->perPaymentAmount != null ) {
			$this -> appendAmount ( $req, $periodicPurchaseInfo->perPaymentAmount );
		}
		if ( $periodicPurchaseInfo->state != null ) {
			$this -> appendPeriodicPurchaseState( $req, $periodicPurchaseInfo->state );
		}
		if ( $periodicPurchaseInfo->schedule != null ) {
			$this -> appendPeriodicPurchaseSchedule( $req, $periodicPurchaseInfo->schedule );
		}
		if ( $periodicPurchaseInfo->orderId != null ) {
			$this -> appendOrderId( $req, $periodicPurchaseInfo->orderId );
		}
		if ( $periodicPurchaseInfo->customerId != null ) {
			$this -> appendParam( $req, "customerId", $periodicPurchaseInfo->customerId );
		}
		if ( $periodicPurchaseInfo->startDate != null ) {
			$this -> appendStartDate( $req, $periodicPurchaseInfo->startDate );
		}
		if ( $periodicPurchaseInfo->endDate != null ) {
			$this -> appendEndDate( $req, $periodicPurchaseInfo->endDate );
		}
		if ( $periodicPurchaseInfo->nextPaymentDate!= null ) {
			$this -> appendDate( $req, "nextPaymentDate", $periodicPurchaseInfo->nextPaymentDate );
		}
	}

	function appendMerchantId( &$req, $merchantId ) {
		$this->appendParam( $req, "merchantId", $merchantId );
	}

	function appendOrderId( &$req, $orderId ) {
		$this->appendParam( $req, "orderId", $orderId );
	}

	function appendStorageFlag( &$req, $flag ) {
		$this->appendParam( $req, "addToStorage", $flag );
	}

	function appendParam( &$req, $name, $value ) {
		if ( is_null( $name ) ) {
			return;
		}
		if ( !( is_null( $value ) ) ) {
			$req[$name] = $value;
		}
	}

	function appendTransactionId( &$req, $transactionId ) {
		$this->appendParam( $req, "transactionId", $transactionId );
	}

	function appendTransactionOrderId( &$req, $transactionOrderId ) {
		$this->appendParam( $req, "transactionOrderId", $transactionOrderId );
	}

	function appendVerificationRequest( &$req, $vr ) {
		if ( $vr != null ) {
			$this->appendParam( $req, "avsRequestCode", $vr->avsRequest );
			$this->appendParam( $req, "cvv2RequestCode", $vr->cvv2Request );

			//Append advance risk profile parameters
			if ( $vr->advancedRiskProfile != null ) {
				if ( $vr->advancedRiskProfile->customer != null ) {
					//Append customer details
					$this->appendParam( $req, "customerEmailHash", $vr->advancedRiskProfile->customer->emailHash );
					$this->appendParam( $req, "customerName", $vr->advancedRiskProfile->customer->customerName );
					$this->appendParam( $req, "customerAnid", $vr->advancedRiskProfile->customer->anid );
					$this->appendParam( $req, "customerGender", $vr->advancedRiskProfile->customer->gender );
					$this->appendParam( $req, "customerDriverLicense", $vr->advancedRiskProfile->customer->driverLicense );
					$this->appendParam( $req, "customerUniqueId", $vr->advancedRiskProfile->customer->uniqueId );
					$this->appendParam( $req, "browserUserAgent", $vr->advancedRiskProfile->customer->userAgent );
					$this->appendParam( $req, "networkIP", $vr->advancedRiskProfile->customer->ip );
					$this->appendParam( $req, "fraudSessionId", $vr->advancedRiskProfile->customer->fraudSessionId );
				}

				if ( $vr->advancedRiskProfile->billingAddress != null ) {
					$this->appendParam( $req, "billingAddress", $vr->advancedRiskProfile->billingAddress->billingAddress );
					$this->appendParam( $req, "billingCity", $vr->advancedRiskProfile->billingAddress->billingCity );
					$this->appendParam( $req, "billingProvince", $vr->advancedRiskProfile->billingAddress->billingProvince );
					$this->appendParam( $req, "billingPostal", $vr->advancedRiskProfile->billingAddress->billingPostal );
					$this->appendParam( $req, "billingCountry", $vr->advancedRiskProfile->billingAddress->billingCountry );
				}

				if ( $vr->advancedRiskProfile->merchandise != null ) {
					$this->appendParam( $req, "transactionSource", $vr->advancedRiskProfile->merchandise->transactionSource );
					$this->appendParam( $req, "billingPhone", $vr->advancedRiskProfile->merchandise->billingPhone );
					$this->appendParam( $req, "shippingPhone", $vr->advancedRiskProfile->merchandise->shippingPhone );
					$this->appendParam( $req, "shippingName", $vr->advancedRiskProfile->merchandise->shippingName );
					$this->appendParam( $req, "shippingType", $vr->advancedRiskProfile->merchandise->shippingType );
				}
			}
		}
	}

	function appendStorageTokenId( &$req, $storageTokenId ) {
		$this->appendParam( $req, "storageTokenId", $storageTokenId );
	}

	function appendTotalNumberInstallments( &$req,
		$totalNumberInstallments ) {
		$this->appendParam( $req, "totalNumberInstallments", $totalNumberInstallments );
	}

	function appendStartDate( &$req, $startDate ) {
		if ( $startDate != null ) {
			$this->appendParam( $req, "startDate", $startDate );
		}
	}

	function appendEndDate( &$req, $endDate ) {
		if ( $endDate != null ) {
			$this->appendParam( $req, "endDate", $endDate );
		}
	}

	function appendPaymentProfile( &$req, $paymentProfile ) {
		if ( $paymentProfile == null ) {
			return;
		} else {
			if ( $paymentProfile->creditCard != null ) {
				$this->appendCreditCard( $req, $paymentProfile->creditCard );
			}
			if ( $paymentProfile->customerProfile != null ) {
				$customerProfile = $paymentProfile->customerProfile;
				$this->appendParam( $req, "profileLegalName", $customerProfile->legalName );
				$this->appendParam( $req, "profileTradeName", $customerProfile->tradeName );
				$this->appendParam( $req, "profileWebsite", $customerProfile->website );
				$this->appendParam( $req, "profileFirstName", $customerProfile->firstName );
				$this->appendParam( $req, "profileLastName", $customerProfile->lastName );
				$this->appendParam( $req, "profilePhoneNumber", $customerProfile->phoneNumber );
				$this->appendParam( $req, "profileFaxNumber", $customerProfile->faxNumber );
				$this->appendParam( $req, "profileAddress1", $customerProfile->address1 );
				$this->appendParam( $req, "profileAddress2", $customerProfile->address2 );
				$this->appendParam( $req, "profileCity", $customerProfile->city );
				$this->appendParam( $req, "profileProvince", $customerProfile->province );
				$this->appendParam( $req, "profilePostal", $customerProfile->postal );
				$this->appendParam( $req, "profileCountry", $customerProfile->country );
			}
		}
	}

	// sends a gateway request
	function send( $request, $receipttype ) {
		if ( $request == null && $receipttype == "creditcard" ) {
			return CreditCardReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, 'a request string is required 25' );
		}

		if ( $request == null && $receipttype == "storage" ) {
			return StorageReceipt::errorOnlyReceipt( REQ_INVALID_REQUEST, 'a request string is required' );
		}

		$queryPairs = array();

		foreach ( $request as $key => $item ) {
			$queryPairs[] .= urlencode( $key ) .'='. urlencode( $item );
		}
		$query = implode( '&', $queryPairs );

		$receipt = null;
		$response = null;

		// open http conn to gateway, post request
		$fp = null;

		//$fp = @fopen($this->url . '?' . $query, 'rb', false);

		if ( phpversion() < 5 ) {
			$fp = @fopen( $this->url . '?' . $query, 'rb', false );
		}
		else {
			$params = array( 'http' => array(
					'method' => 'POST',
					'content' => $query
				) );
			$ctx = stream_context_create( $params );
			$fp = @fopen( $this->url, 'rb', false, $ctx );
		}

		if ( !$fp && $receipttype == "creditcard" ) {
			$receipt = CreditCardReceipt::errorOnlyReceipt( REQ_POST_ERROR, 'error attempting to send POST request' );
		}
		if ( !$fp && $receipttype == "storage" ) {
			$receipt = StorageReceipt::errorOnlyReceipt( REQ_POST_ERROR, 'error attempting to send POST request' );
		}

		$curline = @fgets( $fp );

		if ( $curline == false && $receipttype == "creditcard" ) {
			$receipt = CreditCardReceipt::errorOnlyReceipt( REQ_RESPONSE_ERROR, 'Could not obtain response from the credit card gateway.' );
		}
		if ( $curline == false && $receipttype == "storage" ) {
			$receipt = StorageReceipt::errorOnlyReceipt( REQ_RESPONSE_ERROR, 'Could not obtain response from the credit card gateway.' );
		} else {
			while ( $curline != false ) {
				$response .= $curline;
				$curline = @fgets( $fp );
			}
		}

		@fclose( $fp );
		$fp = null;

		// parse receipt object from response content based on receipt type
		if ( $receipttype == "creditcard" ) {
			$receipt = new CreditCardReceipt( $response );

		}
		if ( $receipttype == "storage" ) {
			$receipt = new StorageReceipt( $response );

		}

		if ( $fp != null ) {
			@fclose( $fp );
		}
		return $receipt;
	}
} // end class

?>
