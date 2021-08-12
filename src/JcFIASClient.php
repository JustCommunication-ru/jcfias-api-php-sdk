<?php

namespace JustCommunication\JcFIASSDK;

use GuzzleHttp\Psr7\Response;
use JustCommunication\JcFIASSDK\API\RequestInterface;
use JustCommunication\JcFIASSDK\API\ResponseInterface;
use JustCommunication\JcFIASSDK\API\TokenRequest;
use JustCommunication\JcFIASSDK\API\TokenResponse;
use JustCommunication\JcFIASSDK\API\JcFIASAPIException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Class JcFIASClient
 *
 * @package JcFIASSDK
 *
 * @method API\SearchResponse sendSearchRequest(API\SearchRequest $request)
 * @method API\SearchBatchResponse sendSearchBatchRequest(API\SearchBatchRequest $request)
 * @method API\TownsResponse sendTownsRequest(API\TownsRequest $request)
 * @method API\RegionsResponse sendRegionsRequest(API\RegionsRequest $request)
 */
class JcFIASClient implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const API_ENDPOINT = 'https://fias.jc9.ru/';

    const DEFAULT_HTTP_CLIENT_OPTIONS = [
        'connect_timeout' => 4,
        'timeout' => 10
    ];

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * JcFIASClient constructor.
     *
     * @param string $username
     * @param string $token
     * @param array|\GuzzleHttp\Client $httpClientOrOptions
     */
    public function __construct($username, $token, $httpClientOrOptions = self::DEFAULT_HTTP_CLIENT_OPTIONS)
    {
        $this->username = $username;
        $this->token = $token;

        $this->httpClient = self::createHttpClient($httpClientOrOptions);

        $this->logger = new NullLogger();
    }

    /**
     * @param array $http_client_options
     * @return \GuzzleHttp\Client
     */
    protected static function createHttpClient($httpClientOrOptions = self::DEFAULT_HTTP_CLIENT_OPTIONS)
    {
        if (is_array($httpClientOrOptions)) {
            $httpClient = new \GuzzleHttp\Client($httpClientOrOptions);
        } else {
            $httpClient = $httpClientOrOptions;
        }

        return $httpClient;
    }

    /**
     * Get token
     *
     * @param string $username
     * @param string $password
     * @param array|\GuzzleHttp\Client $httpClientOrOptions
     * @return TokenResponse
     *
     * @throws JcFIASAPIException
     */
    public static function sendTokenRequest($username, $password, $httpClientOrOptions = self::DEFAULT_HTTP_CLIENT_OPTIONS)
    {
        $httpClient = self::createHttpClient($httpClientOrOptions);

        try {
            $request = new TokenRequest($username, $password);
            $httpResponse = $httpClient->request($request->getHttpMethod(), $request->getUri(), $request->createHttpClientParams());
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            self::handleErrorResponse($e->getResponse());

            throw new JcFIASAPIException('JcFIAS API request error: ' . $e->getMessage());
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            throw new JcFIASAPIException('JcFIAS API error: ' . $e->getMessage());
        }

        /** @var TokenResponse $response */
        $response = self::createAPIResponse($httpResponse, $request->getResponseClass());

        return $response;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param \GuzzleHttp\Client $httpClient
     *
     * @return $this
     */
    public function setHttpClient(\GuzzleHttp\Client $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @param $username
     * @param $password
     * @param array|\GuzzleHttp\Client $httpClientOrOptions
     * @return string
     *
     * @throws JcFIASAPIException
     */
    public static function getToken($username, $password, $httpClientOrOptions = self::DEFAULT_HTTP_CLIENT_OPTIONS)
    {
        $response = self::sendTokenRequest($username, $password, $httpClientOrOptions);
        return $response->getToken();
    }

    public function __call($name, array $arguments)
    {
        if (0 === \strpos($name, 'send')) {
            return call_user_func_array([$this, 'sendRequest'], $arguments);
        }

        throw new \BadMethodCallException(\sprintf('Method [%s] not found in [%s].', $name, __CLASS__));
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     *
     * @throws JcFIASAPIException
     */
    public function sendRequest(RequestInterface $request)
    {
        try {
            /** @var Response $response */
            $response = $this->createAPIRequestPromise($request)->wait();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            self::handleErrorResponse($e->getResponse(), $this->logger);

            throw new JcFIASAPIException('JcFIAS API Request error: ' . $e->getMessage());
        }

        return self::createAPIResponse($response, $request->getResponseClass());
    }

    public function createAPIRequestPromise(RequestInterface $request)
    {
        $request_params = $request->createHttpClientParams();

        $this->logger->debug('JcFIAS API request {method} {uri}', [
            'method' => $request->getHttpMethod(),
            'uri' => $request->getUri(),
            'request_params' => $request_params
        ]);

        $request_params = array_merge_recursive($request_params, [
            'headers' => [
                'X-WSSE' => $this->generateWsseHeader()
            ]
        ]);

        if (!isset($request_params['base_uri'])) {
            $request_params['base_uri'] = self::API_ENDPOINT;
        }

        /*
        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $this->logger,
                new MessageFormatter(MessageFormatter::DEBUG)
            )
        );

        $params['handler'] = $stack;
        */

        return $this->httpClient->requestAsync($request->getHttpMethod(), $request->getUri(), $request_params);
    }

    protected function generateWsseHeader()
    {
        $nonce = hash('sha512', uniqid(true));
        $created = date('c');
        $digest = base64_encode(sha1(base64_decode($nonce) . $created . $this->token, true));

        return sprintf('UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
            $this->username,
            $digest,
            $nonce,
            $created
        );
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $apiResponseClass
     *
     * @return ResponseInterface
     *
     * @throws JcFIASAPIException
     */
    protected static function createAPIResponse(\Psr\Http\Message\ResponseInterface $response, $apiResponseClass)
    {
        $response_string = (string)$response->getBody();
        $response_data = json_decode($response_string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JcFIASAPIException('Invalid response data');
        }

        if (isset($response_data['code'], $response_data['message'])) {
            throw new JcFIASAPIException('JcFIAS API Error: ' . $response_data['message'], $response_data['code']);
        }

        /** @var ResponseInterface $response */
        $response = new $apiResponseClass;

        if (!$response instanceof ResponseInterface) {
            throw new JcFIASAPIException('Invalid response class');
        }

        $response->setResponseData($response_data);

        return $response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @throws JcFIASAPIException
     */
    protected static function handleErrorResponse(\Psr\Http\Message\ResponseInterface $response = null, \Psr\Log\LoggerInterface $logger = null)
    {
        if (!$response) {
            return;
        }

        $response_string = (string)$response->getBody();
        $response_data = json_decode($response_string, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JcFIASAPIException('Unable to decode error response data. Error: ' . json_last_error_msg());
        }

        if (isset($response_data['code'], $response_data['message'])) {
            throw new JcFIASAPIException('JcFIAS API Error: ' . $response_data['message'], $response_data['code']);
        }
    }
}
