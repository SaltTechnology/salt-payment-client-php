<?php

use \SALT\HttpsCreditCardService;
use \SALT\CreditCard;
use \SALT\Merchant;
use \SALT\PaymentProfile;
use \SALT\Schedule;
use \SALT\VerificationRequest;

class HttpsCreditCardServiceTest extends PHPUnit_Framework_TestCase {

    protected $validMerchant;
    protected $invalidMerchant;

    protected $validCard;
    protected $invalidCard;

    protected $validHttpsCardService;
    protected $invalidHttpsCardService;

    protected $validPaymentProfile;
    protected $invalidPaymentProfile;

    protected function setUp() {
        $this->validMerchant = new Merchant ( VALID_MERCHANT_ID, VALID_API_TOKEN ); //TODO: Use different Merchant ID
        $this->invalidMerchant = new Merchant( INVALID_MERCHANT_ID, INVALID_API_TOKEN );

        $this->validHttpsCardService = new HttpsCreditCardService( $this->validMerchant, TEST_GATEWAY_URL );
        $this->invalidHttpsCardService = new HttpsCreditCardService( $this->invalidMerchant, TEST_GATEWAY_URL );

        $this->validCard = new CreditCard( VALID_CARD, CARD_EXPIRY_DATE, null, CARD_STREET, CARD_POSTAL );
        $this->invalidCard = new CreditCard( INVALID_CARD, CARD_EXPIRY_DATE, null, CARD_STREET, CARD_POSTAL );

        $this->validPaymentProfile = new PaymentProfile( $this->validCard, null );
        $this->invalidPaymentProfile = new PaymentProfile( $this->invalidCard, null );
    }

    protected function tearDown() {
        unset( $this->validMerchant );
        unset( $this->invalidMerchant );

        unset( $this->validHttpsCardService );
        unset( $this->invalidHttpsCardService );

        unset( $this->validCard );
        unset( $this->invalidCard );

        unset( $this->validPaymentProfile );
        unset( $this->invalidPaymentProfile );
    }

    /**
     *  Card Storage Tests
     *
     */



    /**
     *
     *
     * @expectedException \SALT\SaltError
     * @expectedExceptionMessage C100_INVALID_MERCHANT_CREDENTIALS
     */
    public function testCardStorageWithInvalidMerchant() {
        $storageToken = uniqid();
        $this->invalidHttpsCardService->addToStorage( $storageToken, $this->validPaymentProfile );
    }

    /**
     *
     *
     * @expectedException \SALT\SaltError
     * @expectedExceptionMessage C200_INVALID_CREDIT_CARD_NUMBER
     */
    public function testCardStorageWithInvalidCard() {
        $storageToken = uniqid();
        $receipt = $this->validHttpsCardService->addToStorage( $storageToken, $this->invalidPaymentProfile );
    }

    public function testCardStorageWithValidMerchantAndCard() {
        $storageToken = uniqid();
        $receipt = $this->validHttpsCardService->addToStorage( $storageToken, $this->validPaymentProfile );

        $this->assertTrue( $receipt->approved );
        //TODO: Check if we want to read the receipt itself?
    }

    /**
     *
     *
     * @expectedException \SALT\SaltError
     * @expectedExceptionMessage C300_STORAGE_TOKEN_ID_ALREADY_IN_USE
     */
    public function testCardStorageWithDuplicateToken() {
        //Store a card
        $storageToken = uniqid();
        $receipt = $this->validHttpsCardService->addToStorage( $storageToken, $this->validPaymentProfile );
        //Now, test with the same storage token.
        $duplicateReceipt = $this->validHttpsCardService->addToStorage( $storageToken, $this->validPaymentProfile );
    }


    /**
     *  Single Purchase Tests
     *
     */

    /**
     *
     *
     * @expectedException \SALT\SaltError
     * @expectedExceptionMessage C200_INVALID_CREDIT_CARD_NUMBER
     */
    public function testSinglePurchaseWithInvalidCard() {
        $vr = new VerificationRequest( SKIP_AVS_VALIDATE, SKIP_CVV_VALIDATE );
        $orderId = uniqid();
        $this->validHttpsCardService->singlePurchase( $orderId, $this->invalidCard, '1000', $vr );
    }

    public function testSinglePurhaseWithValidCard() {
        $vr = new VerificationRequest( SKIP_AVS_VALIDATE, SKIP_CVV_VALIDATE );
        $orderId = uniqid();
        $receipt = $this->validHttpsCardService->singlePurchase( $orderId, $this->validCard, '1000', $vr );
        $this->assertTrue( $receipt->approved );
    }

    /**
     * @expectedException \SALT\SaltError
     */
    public function testSinglePurchaseWithInvalidAmount() {
        $vr = new VerificationRequest( SKIP_AVS_VALIDATE, SKIP_CVV_VALIDATE );
        $orderId = uniqid();
        $receipt = $this->validHttpsCardService->singlePurchase( $orderId, $this->validCard, '-10', $vr );
        // $this->assertTrue( $receipt->approved );
    }

    public function testSinglePurchaseWithValidAmount() {
        $vr = new VerificationRequest( SKIP_AVS_VALIDATE, SKIP_CVV_VALIDATE );
        $orderId = uniqid();
        $receipt = $this->validHttpsCardService->singlePurchase( $orderId, $this->validCard, '100', $vr );
        $this->assertTrue( $receipt->approved );
    }


    /**
     *  Recurring Purchase Tets
     *
     */

    public function testRecurringPurchaseWithValidAmountAndValidCard() {
        $schedule = new Schedule( WEEK, 2 );
        $orderId = uniqid();
        
        //Recurring purchase set from today to a year from now.
        $today = date( "ymd" );
        $next_year = date("Y") + 1;
        $next_year = substr($next_year, -2) . date("md");
        $receipt = $this->validHttpsCardService->recurringPurchase( $orderId, $this->validCard, '1000', $today, $next_year, $schedule, null );
        $this->assertTrue( $receipt->approved );
    }

    /**
     * @expectedException \SALT\SaltError
     */
    public function testRecurringPurchaseWithInvalidAmount() {
        $schedule = new Schedule( WEEK, 2 );
        $orderId = uniqid();


        $today = date( "ymd" );
        $next_year = date("Y") + 1;
        $next_year = substr($next_year, -2) . date("md");
        $receipt = $this->validHttpsCardService->recurringPurchase( $orderId, $this->validCard, '-1000', $today, $next_year, $schedule, null );
        $this->assertFalse( $receipt->approved );
    }

    /**
     * @expectedException \SALT\SaltError
     */
    public function testRecurringPurchaseWithInvalidDateFormat() {
        $schedule = new Schedule( WEEK, 2 );
        $orderId = uniqid();
        
        $today = '112x2'; //InvalidFormat
        $next_year = date("Y") + 1;
        $next_year = substr($next_year, -2) . date("md");
        $receipt = $this->validHttpsCardService->recurringPurchase( $orderId, $this->validCard, '1000', $today, $next_year, $schedule, null );
        $this->assertTrue( $receipt->approved );
    }

    /**
     * @expectedException \SALT\SaltError
     * @expectedExceptionMessage C200_INVALID_CREDIT_CARD_NUMBER
     */
    public function testRecurringPurchaseWithInvalidCard() {
        $schedule = new Schedule( WEEK, 2 );
        $orderId = uniqid();
        
        //Recurring purchase set from today to a year from now.
        $today = date( "ymd" );
        $next_year = date("Y") + 1;
        $next_year = substr($next_year, -2) . date("md");
        $receipt = $this->validHttpsCardService->recurringPurchase( $orderId, $this->invalidCard, '1000', $today, $next_year, $schedule, null );
    }

    public function testBatchClosure() {
        $this->fail("Please implement this test.");
    }

    public function testSinglePurchaseRefund() {
        $this->fail("Please implement this test.");
    }

}
