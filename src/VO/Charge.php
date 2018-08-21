<?php
namespace Vload\Common\VO;

class Charge
{
    const STATUS_SUCCESS = 'succeeded';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';

    private $id;
    private $amount;
    private $currency;
    private $description;
    private $customer;
    private $voucher;
    private $orderId;
    private $status;

    public function __construct($id, $amount, $currency, $description, $customer, $voucher, $orderId, $status)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->customer = $customer;
        $this->voucher = $voucher;
        $this->orderId = $orderId;
        $this->status = $status;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getVoucher()
    {
        return $this->voucher;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
