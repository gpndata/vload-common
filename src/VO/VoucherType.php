<?php
namespace Vload\Common\VO;

class VoucherType
{
    /** @var string */
    private $id;
    /** @var int */
    private $value;
    /** @var string */
    private $currency;
    /** @var bool */
    private $enabled;

    public function __construct($id, $value, $currency, $enabled)
    {
        $this->id = $id;
        $this->value = $value;
        $this->currency = $currency;
        $this->enabled = !!$enabled;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
}
