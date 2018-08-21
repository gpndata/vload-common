<?php
namespace Vload\Common\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Vload\Common\Exception\CommunicationFailed;
use Vload\Common\Exception\Conflict;
use Vload\Common\Exception\InvalidInput;
use Vload\Common\Exception\NotFound;
use Vload\Common\Exception\Unauthorized;

class Client
{
    /** @var string */
    private $secret;
    /** @var string */
    private $apiUrl;
    /** @var GuzzleClient */
    private $guzzle;

    /**
     * @param string $secret
     * @param string $apiUrl
     * @param GuzzleClient $guzzleClient
     */
    public function __construct($secret, $apiUrl = 'https://api.vload.expert/v1/', $guzzleClient = null)
    {
        $this->secret = $secret;
        $this->apiUrl = $apiUrl;
        if (!($guzzleClient instanceof GuzzleClient)) {
            $guzzleClient = new GuzzleClient();
        }
        $this->guzzle = $guzzleClient;
    }

    /**
     * @param string $endpoint
     * @return array
     * @throws CommunicationFailed
     * @throws Conflict
     * @throws InvalidInput
     * @throws NotFound
     * @throws Unauthorized
     */
    public function get($endpoint)
    {
        $options = $this->createRequestOptions();
        return $this->request('get', $endpoint, $options);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws CommunicationFailed
     * @throws Conflict
     * @throws InvalidInput
     * @throws Unauthorized
     * @throws NotFound
     */
    public function post($endpoint, $params)
    {
        $options = $this->createRequestOptions($params);
        return $this->request('post', $endpoint, $options);
    }

    /**
     * @param string $endpoint
     * @return array
     * @throws CommunicationFailed
     * @throws Conflict
     * @throws InvalidInput
     * @throws NotFound
     * @throws Unauthorized
     */
    public function delete($endpoint)
    {
        $options = $this->createRequestOptions();
        return $this->request('delete', $endpoint, $options);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array
     * @throws CommunicationFailed
     * @throws Conflict
     * @throws InvalidInput
     * @throws NotFound
     * @throws Unauthorized
     */
    private function request($method, $endpoint, $options)
    {
        $uri = $this->apiUrl . $endpoint;
        try {
            $response = $this->guzzle->$method($uri, $options);
        } catch (RequestException$e) {
            $response = $e->getResponse();
            $this->determineFailureAndThrow($response);
        }
        $body = $response->getBody()->getContents();
        if (empty($body)) {
            return [];
        }
        return json_decode($body, true);
    }

    /**
     * @param array $params
     * @return array
     */
    private function createRequestOptions($params = [])
    {
        $options = [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $this->secret
            ],
        ];
        if (!empty($params)) {
            $options[RequestOptions::FORM_PARAMS] = $params;
        }
        return $options;
    }

    /**
     * @param ResponseInterface $response
     * @throws CommunicationFailed
     * @throws Conflict
     * @throws InvalidInput
     * @throws NotFound
     * @throws Unauthorized
     */
    private function determineFailureAndThrow($response)
    {
        $content = json_decode($response->getBody()->getContents(), true);
        if (
            $content === null
            || !array_key_exists('message', $content)
            || !array_key_exists('code', $content)
        ) {
            throw new CommunicationFailed('Unrecognized format of API response');
        }
        $httpCode = (string)$response->getStatusCode();
        $errorMessage = $content['message'];
        $errorCode = $content['code'];
        switch ($httpCode) {
            case '400':
                throw new InvalidInput($errorMessage, $errorCode);
            case '401':
                throw new Unauthorized($errorMessage, $errorCode);
            case '404':
                throw new NotFound($errorMessage, $errorCode);
            case '409':
                throw new Conflict($errorMessage, $errorCode);
            default:
                throw new CommunicationFailed($errorMessage, $errorCode);
        }
    }
}
