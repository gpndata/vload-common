<?php
namespace Vload\Common\VO;

class Voucher
{
    /** @var string */
    private $id;
    /** @var string */
    private $pin;
    /** @var int */
    private $value;
    /** @var string */
    private $currency;
    /** @var string */
    private $type;
    /** @var \DateTimeImmutable */
    private $created;

    public function __construct($id, $pin, $value, $currency, $type, $created)
    {
        $this->id = $id;
        $this->pin = $pin;
        $this->value = $value;
        $this->currency = $currency;
        $this->type = $type;
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreated()
    {
        return $this->created;
    }
}
