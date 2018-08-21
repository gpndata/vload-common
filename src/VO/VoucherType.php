<?php
namespace Vload\Common\VO;

class VoucherType
{
    private $id;
    private $value;
    private $currency;
    private $enabled;

    public function __construct($id, $value, $currency, $enabled)
    {
        $this->id = $id;
        $this->value = $value;
        $this->currency = $currency;
        $this->enabled = $enabled;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }
}
