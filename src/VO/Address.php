<?php
namespace Vload\Common\VO;

class Address
{
    /** @var string */
    private $city;
    /** @var string */
    private $country;
    /** @var string */
    private $addressLine1;
    /** @var string */
    private $addressLine2;
    /** @var string */
    private $state;
    /** @var string */
    private $zip;

    public function __construct(
        $city = null,
        $country = null,
        $addressLine1 = null,
        $addressLine2 = null,
        $state = null,
        $zip = null
    ) {
        $this->city = $city;
        $this->country = $country;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->state = $state;
        $this->zip = $zip;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getZip()
    {
        return $this->zip;
    }
}
