<?php
namespace Salt;
class PeriodicPurchaseInfo {
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

    public function __construct( $periodicTransactionId, $state, $schedule = null, $perPaymentAmount = null,
        $orderId = null, $customerId = null, $startDate = null, $endDate = null, $nextPaymentDate = null, $lastPaymentId = null ) {
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

    public function __get( $property ) {
        if ( property_exists( $this, $property ) ) {
            return $this->$property;
        }

    }

    public function __set( $property, $value ) {
        if ( property_exists( $this, $property ) ) {
            $this->$property = $value;
        }


        return $this;
    }


    function __toString() {
        if ( isset( $state ) && isset( $schedule ) && isset( $perPaymentAmount ) && isset( $orderId ) && isset( $customerId ) && isset( $startDate ) && isset( $endDate ) && isset( $nextPaymentDate ) && isset( $lastPaymentId ) )
            return $state.$schedule.$perPaymentAmount.$orderId.$customerId.$startDate.$endDate.$nextPaymentDate.$lastPaymentId;
    }


}
