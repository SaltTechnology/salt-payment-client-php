<?php

// SALT Client API for PHP >= 5.3

include 'Constants.php';

// classes

class ApprovalInfo {
    private $authorizedAmount = 0;
    private $approvalCode = null;
    private $traceNumber = null;
    private $referenceNumber = null;
    // constructor
    public function __construct($authorizedAmount, $approvalCode, $traceNumber, $referenceNumber) {
        $this->authorizedAmount = $authorizedAmount;
        $this->approvalCode = $approvalCode;
        $this->traceNumber = $traceNumber;
        $this->referenceNumber = $referenceNumber;
    }
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
    function __toString() {

        if(isset($authorizedAmount) && isset($approvalCode) && isset($traceNumber) &&
            isset($referenceNumber))
        {
            return $authorizedAmount.$approvalCode.$traceNumber.$referenceNumber;
        }
    }


} // ApprovalInfo

class AvsResponse {
    private $avsResponseCode;
    private $streetMatched;
    private $zipMatched;
    private $zipType;
    private $avsErrorCode;
    private $avsErrorMessage;
    // constructor
    public function __construct($avsResponseCode,$streetMatched, $zipMatched, $zipType,  $avsErrorCode, $avsErrorMessage) {
        $this->avsResponseCode = $avsResponseCode;
        $this->streetMatched = $streetMatched;
        $this->zipMatched = $zipMatched;
        $this->zipType = $zipType;
        $this->avsErrorCode = $avsErrorCode;
        $this->avsErrorMessage = $avsErrorMessage;
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
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
    function __toString() {
        if(isset($avsResponseCode) && isset($streetMatched) &&
            isset($zipMatched) && isset($zipType) && isset($avsErrorCode) && isset($avsErrorMessage))
            return $avsResponseCode.$streetMatched.$zipMatched.$zipType.$avsErrorCode.$avsErrorMessage;
    }


} // AvsResponse


class AvsRequest{
    private $code = null;

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
    function __toString() {
        if(isset($code))
            return $code;
    }

    public function __construct($code){
        $this->code = $code;
    }
}//AvsRequest

class CreditCard {
    private $creditCardNumber;
    private $expiryDate;
    private $cvv2;
    private $street;
    private $zip;
    private $secureCode;
    private $cardHolderName;
    // constructor
    public function __construct($creditCardNumber, $expiryDate, $cvv2 = null,
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

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
    function __toString() {

        if(isset($creditCardNumber) && isset($expiryDate) && isset($cvv2) && isset($street)
            && isset($zip) && isset($secureCode) && isset($cardHolderName)) {
            return $creditCardNumber . $expiryDate . $cvv2 . $street . $zip . $secureCode . $cardHolderName;
        }
    }

} // CreditCard

class CreditCardReceipt {
    private $params = null;
    private $approved = false;
    private $transactionId = null;
    private $orderId = null;
    private $processedDateTime = null;	// as a string
    private $processedDateTimestamp = null;	// as an int (can apply your own formatting to this value)
    private $errorCode = null;
    private $errorMessage = null;
    private $debugMessage = null;
    private $approvalInfo = null;
    private $avsResponse = null;
    private $cvv2Response = null;
    private $response = null;
    private $periodicPurchaseInfo = null;
    private $fraudScore = null;
    private $fraudDecision = null;

    // constructor parses response from gateway into this object
    public function __construct($response = null) {
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

            try{



                list($paramKey, $paramValue) = array_pad(explode("=", $lines[$i],2), 2, null);

//list($paramKey, $paramValue) = explode("=", $lines[$i]);
            }
            catch(Exception $e)
            {
                echo '<span style="color: red;" />'.$e->getMessage()."<br/>".'</span>';
            }

            $this->params[$paramKey] = $paramValue;



        }


        // parse the param into data class objects


        if(isset($this->params["APPROVED"]))
        {
            $this->approved = $this->params["APPROVED"] == 'true';}
        else
        {

            echo '<span style="color: red;" />'.UNDEFINED_CREDIT_CARD_INFO."<br/>".'</span>';
        }
        if(isset($this->params["TRANSACTION_ID"])){

            $this->transactionId = $this->params["TRANSACTION_ID"];}
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_TRANSACTION_INFO."<br/>".'</span>';
        }
        if(isset($this->params["ORDER_ID"]))
        {
            $this->orderId = $this->params["ORDER_ID"];
        }

        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_ORDER_ID."<br/>".'</span>';
        }
        if(isset($this->params["PROCESSED_DATE"]))
        {
            // returned date time is in yymmddhhiiss format
            $processedDate = $this->params["PROCESSED_DATE"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_PROCESSED_DATE."<br/>".'</span>';
        }
        if(isset($this->params["PROCESSED_TIME"]))
        {
            $processedTime = $this->params["PROCESSED_TIME"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_PROCESSED_TIME."<br/>".'</span>';
        }


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
        if(isset($this->params["ERROR_CODE"]))
        {
            $this->errorCode = $this->params["ERROR_CODE"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_ERROR_CODE."<br/>".'</span>';
        }

        if(isset($this->params["ERROR_MESSAGE"])){
            $this->errorMessage = $this->params["ERROR_MESSAGE"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_ERROR_MESSAGE."<br/>".'</span>';
        }
        if(isset($this->params["DEBUG_MESSAGE"]))
        {
            $this->debugMessage = $this->params["DEBUG_MESSAGE"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_DEBUG_MESSAGE."<br/>".'</span>';
        }

        // parse the approval info

        if(isset($this->params["AUTHORIZED_AMOUNT"])=== false)
        {
            echo '<span style="color: red;" />'.UNDEFINED_AUTHORIZED_AMOUNT."<br/>".'</span>';
        }

        if(isset($this->params["APPROVAL_CODE"])===false)
        {
            echo '<span style="color: red;" />'.UNDEFINED_APPROVAL_CODE."<br/>".'</span>';
        }


        if(isset($this->params["TRACE_NUMBER"])===false)
        {
            echo '<span style="color: red;" />'.UNDEFINED_TRACE_NUMBER."<br/>".'</span>';
        }

        if(isset($this->params["REFERENCE_NUMBER"])===false)
        {
            echo '<span style="color: red;" />'.UNDEFINED_REFERENCE_NUMBER."<br/>".'</span>';
        }

        if(isset($this->params["AUTHORIZED_AMOUNT"]) && isset($this->params["APPROVAL_CODE"]) && isset($this->params["TRACE_NUMBER"]) && isset($this->params["REFERENCE_NUMBER"]))
        {

            if ($this->approved) {
                $this->approvalInfo = new ApprovalInfo(

                    $this->params["AUTHORIZED_AMOUNT"],
                    $this->params["APPROVAL_CODE"],
                    $this->params["TRACE_NUMBER"],
                    $this->params["REFERENCE_NUMBER"]);

            } else {
                $this->approvalInfo = null;
            }

        }

        if(isset($this->params["AVS_RESPONSE_AVAILABLE"]))
        {
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
            }
            else
            {
                echo '<span style="color: red;" />'.UNDEFINED_AVS_RESPONSE."<br/>".'</span>';
            }

            if(isset($this->params["AVS_RESPONSE_CODE"])===false)
            {
                echo '<span style="color: red;" />'.UNDEFINED_AVS_RESPONSE_CODE."<br/>".'</span>';
            }
            if(isset($this->params["STREET_MATCHED"])===false)
            {
                echo '<span style="color: red;" />'.UNDEFINED_STREET_MATCHED."<br/>".'</span>';
            }
            if(isset($this->params["ZIP_MATCHED"])===false)
            {
                echo '<span style="color: red;" />'.UNDEFINED_ZIP_MATCHED."<br/>".'</span>';
            }
            if(isset($this->params["ZIP_TYPE"])===false)
            {
                echo '<span style="color: red;" />'.UNDEFINED_ZIP_TYPE."<br/>".'</span>';
            }
            if(isset($this->params["AVS_RESPONSE_CODE"]) && isset($this->params["STREET_MATCHED"]) && isset($this->params["ZIP_MATCHED"])
                && isset($this->params["ZIP_TYPE"]))
            {
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
        }
        if(isset($this->params["CVV2_RESPONSE_MESSAGE"])===false)
        {
            echo '<span style="color: red;" />'.UNDEFINED_CVV2_RESPONSE_MESSAGE."<br/>".'</span>';
        }
        if(isset($this->params["CVV2_RESPONSE_CODE"])===false)
        {
            echo '<span style="color: red;" />'.UNDEFINED_CVV2_RESPONSE_CODE."<br/>".'</span>';
        }


        if(isset($this->params["CVV2_RESPONSE_MESSAGE"]) && isset($this->params["CVV2_RESPONSE_CODE"]))
        {


            // parse the CVV2 response
            $cvv2ResponseAvailable = $this->params["CVV2_RESPONSE_AVAILABLE"];


            if ($cvv2ResponseAvailable != null && $cvv2ResponseAvailable) {
                $this->cvv2Response = new Cvv2Response(
                    $this->params["CVV2_RESPONSE_CODE"],
                    $this->params["CVV2_RESPONSE_MESSAGE"]);
            } else {
                $this->cvv2Response = null;
            }


        }
        if(isset($this->params["FRAUD_SCORE"])){

            //Parse fraud related parameters
            $this->fraudScore = $this->params["FRAUD_SCORE"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_FRAUD_SCORE."<br/>".'</span>';
        }
        if(isset($this->params["FRAUD_DECISION"])){

            $this->fraudDecision = $this->params["FRAUD_DECISION"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_FRAUD_DESICION."<br/>".'</span>';
        }

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

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
    function __toString() {

        if(isset($params) && isset($approved) && isset($transactionId) && isset($orderId)
            && isset($processedDateTime) && isset($processedDateTimestamp) && isset($errorCode) && isset($errorMessage) && isset($debugMessage)  && isset($approvalInfo)  && isset($response) && isset($avsResponse) && isset($cvv2Response) && isset($periodicPurchaseInfo) && isset($fraudScore) && isset($fraudDecision))
            return $params.$approved.$transactionId.$orderId.$processedDateTime.$processedDateTimestamp.$errorCode.$errorMessage.$debugMessage.$approvalInfo.$avsResponse.$cvv2Response.$response.$periodicPurchaseInfo.$fraudScore.$fraudDecision;
    }


} // CreditCardReceipt

class Cvv2Response {
    private $code;
    private $message;
    public function __construct($code, $message) {
        $this->code = $code;
        $this->message = $message;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    function __toString() {
        if(isset($code) && isset($message))
            return $code.$message;
    }

}// Cvv2Response

class Cvv2Request{
    private $code = null;

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
    public function __construct($code){
        $this->code = $code;
    }
    function __toString() {
        if(isset($code))
            return $code;
    }

}// Cvv2Request

class VerificationRequest {
    private $avsRequest = null;
    private $cvv2Request = null;
    private $advancedRiskProfile = null;

    // constructor
    public function __construct($avsRequest, $cvv2Request,$advancedRiskProfile=null) {
        $this->avsRequest = $avsRequest;
        $this->cvv2Request = $cvv2Request;
        $this->advancedRiskProfile = $advancedRiskProfile;
    }
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
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

    function __toString() {

        if(isset($avsRequest) && isset($cvv2Request) && isset($advancedRiskProfile))
            return $avsRequest.$cvv2Request.$advancedRiskProfile;
    }

} // VerificationRequest

class CustomerProfile {
    private $legalName = null;
    private $tradeName = null;
    private $website = null;
    private $firstName = null;
    private $lastName = null;
    private $phoneNumber = null;
    private $faxNumber = null;
    private $address1 = null;
    private $address2 = null;
    private $city = null;
    private $province = null;
    private $postal = null;
    private $country = null;

    public function __construct ()
    {}

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }


    function __toString() {
        if(isset($legalName) && isset($tradeName) && isset($website) && isset($firstName) && isset($lastName) && isset($phoneNumber) && isset($faxNumber) && isset($address1) && isset($address2) && isset($city) && isset($province) && isset($postal) && isset($country))
            return $legalName.$tradeName.$website.$firstName.$lastName.$phoneNumber.$faxNumber.$address1.$address2.$city.$province.$postal.$country;

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
    private $params = null;
    private $approved = false;
    private $transactionId = null;
    private $orderId = null;
    private $processedDateTime = null;
    private $errorCode = null;
    private $errorMessage = null;
    private $debugMessage = null;
    private $response = null;
    private $paymentProfile= null;
    private $storageTokenId = null;

    public function __construct ($response)
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
        if(isset($this->params["STORAGE_TOKEN_ID"])){

            $this->storageTokenId = $this->params["STORAGE_TOKEN_ID"];
        }
        else
        {
            echo '<span style="color: red;" />'.UNDEFINED_STORAGE_TOKEN."<br/>".'</span>';
        }

        if(isset($this->params["ERROR_CODE"]))
        {
            $this->errorCode = $this->params["ERROR_CODE"];
        }
        if(isset($this->params["ERROR_MESSAGE"]))
        {
            $this->errorMessage = $this->params["ERROR_MESSAGE"];
        }
        if(isset($this->params["DEBUG_MESSAGE"]))
        {
            $this->debugMessage = $this->params["DEBUG_MESSAGE"];
        }

        if(isset($this->params["PAYMENT_PROFILE_AVAILABLE"])){
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
                    if(isset($this->params["CUSTOMER_PROFILE_LEGAL_NAME"]))
                    {
                        $profile->setLegalName($this->params["CUSTOMER_PROFILE_LEGAL_NAME"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_TRADE_NAME"]))
                    {
                        $profile->setTradeName($this->params["CUSTOMER_PROFILE_TRADE_NAME"]);
                    }

                    if(isset($this->params["CUSTOMER_PROFILE_WEBSITE"]))
                    {

                        $profile->setWebsite($this->params["CUSTOMER_PROFILE_WEBSITE"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_FIRST_NAME"]))
                    {
                        $profile->setFirstName($this->params["CUSTOMER_PROFILE_FIRST_NAME"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_LAST_NAME"]))
                    {
                        $profile->setLastName($this->params["CUSTOMER_PROFILE_LAST_NAME"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_PHONE_NUMBER"]))
                    {
                        $profile->setPhoneNumber($this->params["CUSTOMER_PROFILE_PHONE_NUMBER"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_FAX_NUMBER"]))
                    {
                        $profile->setFaxNumber($this->params["CUSTOMER_PROFILE_FAX_NUMBER"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_ADDRESS1"]))
                    {
                        $profile->setAddress1($this->params["CUSTOMER_PROFILE_ADDRESS1"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_ADDRESS2"]))
                    {
                        $profile->setAddress2($this->params["CUSTOMER_PROFILE_ADDRESS2"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_CITY"]))
                    {
                        $profile->setCity($this->params["CUSTOMER_PROFILE_CITY"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_PROVINCE"]))
                    {
                        $profile->setProvince($this->params["CUSTOMER_PROFILE_PROVINCE"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_POSTAL"]))
                    {
                        $profile->setPostal($this->params["CUSTOMER_PROFILE_POSTAL"]);
                    }
                    if(isset($this->params["CUSTOMER_PROFILE_COUNTRY"]))
                    {
                        $profile->setCountry($this->params["CUSTOMER_PROFILE_COUNTRY"]);
                    }
                }
                $this->paymentProfile = new PaymentProfile($creditCard, $profile);
            }

            else {
                $this->paymentProfile = null;
            }

        }
        else
        {

            echo '<span style="color: red;" />'.UNDEFINED_PAYMENT_PROFILE_AVAILABILITY."<br/>".'</span>';
        }



    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }


    function __toString() {
        if(isset($params) && isset($approved) && isset($transactionId) && isset($orderId) && isset($processedDateTime) && isset($errorCode) && isset($errorMessage) && isset($debugMessage) && isset($response) && isset($paymentProfile) && isset($storageTokenId) )
            return $params.$approved.$transactionId.$orderId.$processedDateTime.$errorCode.$errorMessage.$debugMessage.$response.$paymentProfile.$storageTokenId ;


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
    private $scheduleType = null;
    private $intervalLength = null;

    public function __construct($type, $intervalLength) {
        $this->scheduleType = $type;
        $this->intervalLength = $intervalLength;
    }
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }


        return $this;
    }

    function __toString() {
        if(isset($scheduleType) && isset($intervalLength))
            return $scheduleType.$intervalLength;
    }

}

class PeriodicPurchaseInfo
{
    private $periodicTransactionId = null;
    private $lastPaymentId = null;
    private $state = null;
    private $schedule = null;
    private $perPaymentAmount = null;
    private $orderId = null;
    private $customerId = null;
    private $startDate = null;
    private $endDate = null;
    private $nextPaymentDate = null;

    public function __construct($periodicTransactionId, $state, $schedule = null, $perPaymentAmount = null,
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

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }


        return $this;
    }


    function __toString() {
        if(isset($state) && isset($schedule) && isset($perPaymentAmount) && isset($orderId) && isset($customerId) && isset($startDate) && isset($endDate) && isset($nextPaymentDate) && isset($lastPaymentId))
            return $state.$schedule.$perPaymentAmount.$orderId.$customerId.$startDate.$endDate.$nextPaymentDate.$lastPaymentId;
    }


}

class PaymentProfile
{
    private $creditCard = null;
    private $customerProfile = null;

    public function __construct($creditCard, $customerProfile) {
        $this->creditCard = $creditCard;
        $this->customerProfile = $customerProfile;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }


        return $this;
    }

    function __toString() {
        if(isset($creditCard) && isset($customerProfile))
            return $creditCard.$customerProfile;
    }

}

class Merchant
{
    private $merchantId;
    private $apiToken;
    private $storeId;

    public function __construct($merchantId, $apiToken, $storeId = null){
        $this->merchantId = $merchantId;
        $this->apiToken = $apiToken;
        $this->storeId = $storeId;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }



        return $this;
    }

    function __toString(){
        if(isset($merchantId) && isset($apiToken) && isset($storeId))
            return $merchantId.$apiToken.$storeId;
    }

}

class Customer {
    private $emailHash = null;
    private $customerName = null;
    private $gender = null;
    private $driverLicense = null;
    private $fraudSessionId = null;
    private $anid = null;
    private $uniqueId = null;
    private $ip = null;
    private $userAgent = null;

    public function __construct($fraudSessionId,$emailHash,$customerName,$gender,$driverLicense,$anid,$uniqueId,$ip,$userAgent){
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

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }



        return $this;
    }

    function __toString() {
        if(isset($emailHash) && isset($customerName) && isset($gender) && isset($driverLicense) && isset($fraudSessionId) && isset($anid) && isset($uniqueId) && isset($ip) && isset($userAgent))
            return $emailHash.$customerName.$gender.$driverLicense.$fraudSessionId.$anid.$uniqueId.$ip.$userAgent;
    }


}

class Merchandise{
    private $billingPhone = null;
    private $shippingPhone = null;
    private $shippingName = null;
    private $shippingType = null;
    private $transactionSource = null;

    public function __construct($billingPhone,$shippingPhone,$shippingName,$shippingType,$transactionSource)
    {
        $this->$billingPhone = $billingPhone;
        $this->$shippingPhone = $shippingPhone;
        $this->$shippingName = $shippingName;
        $this->$shippingType = $shippingType;
        $this->$transactionSource = $transactionSource;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }



        return $this;
    }

    function __toString() {
        if(isset($billingPhone) && isset($shippingPhone) && isset($shippingName) && isset($shippingType) && isset($transactionSource))
            return $billingPhone.$shippingPhone.$shippingName.$shippingType.$transactionSource;
    }



}

class Address {
    private $address = null;
    private $city = null;
    private $province = null;
    private $postal = null;
    private $country = null;

    public function __construct($address,$city, $province, $postal, $country){
        $this->address = $address;
        $this->city = $city;
        $this->province = $province;
        $this->postal = $postal;
        $this->country = $country;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    function __toString() {
        if(isset($address) && isset($city) && isset($province) && isset($postal) && isset($country))
            return $address.$city.$province.$postal.$country;
    }


}



class AdvancedRiskProfile {
    private $customer = null;
    private $billingAddress = null;
    private $merchandise = null;

    public function __construct($customer,$billingAddress, $merchandise){
        $this->customer = $customer;
        $this->billingAddress = $billingAddress;
        $this->merchandise = $merchandise;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    function __toString() {
        if(isset($customer) && isset($billingAddress) && isset($merchandise))
            return $customer.$billingAddress.$merchandise;
    }

}
?>