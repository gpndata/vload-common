<?php
namespace Vload\Common\Api;

use Vload\Common\VO\Address;
use Vload\Common\VO\Customer;

class VOConverter
{
    /**
     * @param $object
     * @return array
     */
    public static function convertForRequest($object)
    {
        $converter = self::determineConverter($object);
        if (!method_exists(VOConverter::class, $converter)) {
            throw new \RuntimeException('Failed to convert: unsupported object');
        }
        $data = self::$converter($object);
        return $data;
    }

    /**
     * @param $object
     * @return string
     */
    private static function determineConverter($object)
    {
        $namespacedClassName = get_class($object);
        $classNameParts = explode('\\', $namespacedClassName);
        $className = array_pop($classNameParts);
        $converter = 'convert' . $className;
        return $converter;
    }

    /**
     * @param Customer $customer
     * @return array
     */
    private static function convertCustomer($customer)
    {
        $data = [
            'id' => $customer->getId(),
        ];
        if (null !== $customer->getFirstName()) {
            $data['firstname'] = $customer->getFirstName();
        }
        if (null !== $customer->getFirstName()) {
            $data['lastname'] = $customer->getLastName();
        }
        if (null !== $customer->getFirstName()) {
            $data['email'] = $customer->getEmail();
        }
        if (null !== $customer->getPhone()) {
            $data['phone'] = $customer->getPhone();
        }
        if (null !== $customer->getIp()) {
            $data['ip_address'] = $customer->getIp();
        }
        if (null !== $customer->getAddress()) {
            $address = self::convertForRequest($customer->getAddress());
            $data = array_merge($data, $address);
        }
        return $data;
    }

    /**
     * @param Address $address
     * @return array
     */
    private static function convertAddress($address)
    {
        $data = [];
        if (null !== $address->getCity()) {
            $data['address_city'] = $address->getCity();
        }
        if (null !== $address->getCountry()) {
            $data['address_country'] = $address->getCountry();
        }
        if (null !== $address->getAddressLine1()) {
            $data['address_line1'] = $address->getAddressLine1();
        }
        if (null !== $address->getAddressLine2()) {
            $data['address_line2'] = $address->getAddressLine2();
        }
        if (null !== $address->getState()) {
            $data['address_state'] = $address->getState();
        }
        if (null !== $address->getZip()) {
            $data['address_zip'] = $address->getZip();
        }

        return $data;
    }
}
