<?php
namespace Vload\Common\Api;

class Config
{
    private $client;

    private function __construct($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public static function init($secret, $apiUrl = 'https://api.vload.expert/v1/')
    {
        $client = new Client($secret, $apiUrl);
        return new Config($client);
    }

    public static function initWithClient($client)
    {
        if (!($client instanceof Client)) {
            throw new \InvalidArgumentException('Config must be initialized with Vload\Api\Client');
        }
        return new Config($client);
    }
}
