<?php

use \SALT\HttpsCreditCardService;
use \SALT\CreditCard;
use \SALT\Merchant;
use \SALT\PaymentProfile;

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

        $this->validPaymentProfile = new PaymentProfile($this->validCard, null);
        $this->invalidPaymentProfile = new PaymentProfile($this->invalidCard, null);
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
     * @expectedException \SALT\SaltError
     * @expectedExceptionMessage C100_INVALID_MERCHANT_CREDENTIALS
     */
    public function testCardStorageWithInvalidMerchant() {
        $storageToken = uniqid();
        $this->invalidHttpsCardService->addToStorage( $storageToken, $this->validPaymentProfile );
    }

    /**
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

    public function testSinglePurchaseWithInvalidAmount() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testSinglePurchaseWithValidAmount() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testSinglePurchaseWithInvalidToken() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testSinglePurhaseWithValidToken() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testSinglePurchaseWithInvalidCvv() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testSinglePurchaseWithValidCvv() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testRecurringPurchaseWithValidAmount() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testRecurringPurchaseWithInvalidAmount() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testRecurringPurchaseWithValidCard() {
        $this->fail( 'Please implement this test case.' );
    }

    public function testRecurringPurchaseWithInvalidCard() {
        $this->fail( 'Please implement this test case.' );
    }

}
