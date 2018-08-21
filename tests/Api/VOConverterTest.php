<?php
namespace Vload\CommonTest\Api;

use PHPUnit\Framework\TestCase;
use Vload\Common\Api\VOConverter;
use Vload\Common\VO\Address;
use Vload\Common\VO\Customer;

class VOConverterTest extends TestCase
{

    public function testConvertUnknownObject()
    {
        $object = (object)['id' => 1];
        $this->expectException(\RuntimeException::class);
        VOConverter::convertForRequest($object);
    }

    public function testConvertCustomer()
    {
        $expected = [
            'id' => 1,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+48123123123',
            'ip' => '127.0.0.1',
        ];

        $customer = new Customer(
            $expected['id'],
            $expected['firstname'],
            $expected['lastname'],
            $expected['email'],
            $expected['phone'],
            null,
            $expected['ip']
        );

        $data = VOConverter::convertForRequest($customer);
        $this->assertEquals($expected, $data);
    }

    public function testConvertAddress()
    {
        $expected = [
            'address_city' => 'Warsaw',
            'address_country' => 'PL',
            'address_line1' => 'Kubusia Puchatka 1',
            'address_line2' => 'top floor',
            'address_state' => 'MZ',
            'address_zip' => '02-002',
        ];

        $address = new Address(
            $expected['address_city'],
            $expected['address_country'],
            $expected['address_line1'],
            $expected['address_line2'],
            $expected['address_state'],
            $expected['address_zip']
        );

        $data = VOConverter::convertForRequest($address);
        $this->assertEquals($expected, $data);
    }

    public function testConvertCustomerWithAddress()
    {
        $expected = [
            'id' => 1,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+48123123123',
            'ip' => '127.0.0.1',
            'address_city' => 'Warsaw',
            'address_country' => 'PL',
            'address_line1' => 'Kubusia Puchatka 1',
            'address_line2' => 'top floor',
            'address_state' => 'MZ',
            'address_zip' => '02-002',
        ];

        $address = new Address(
            $expected['address_city'],
            $expected['address_country'],
            $expected['address_line1'],
            $expected['address_line2'],
            $expected['address_state'],
            $expected['address_zip']
        );

        $customer = new Customer(
            $expected['id'],
            $expected['firstname'],
            $expected['lastname'],
            $expected['email'],
            $expected['phone'],
            $address,
            $expected['ip']
        );

        $data = VOConverter::convertForRequest($customer);
        $this->assertEquals($expected, $data);
    }
}
