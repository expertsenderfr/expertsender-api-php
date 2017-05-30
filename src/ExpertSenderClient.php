<?php

namespace TDF\ExpertSenderApi;

use TDF\ExpertSenderApi\Model\Template;
use TDF\ExpertSenderApi\Services\Messages;
use TDF\ExpertSenderApi\Services\Segmentations;
use TDF\ExpertSenderApi\Services\Server;
use TDF\ExpertSenderApi\Services\SignalSpamStatistics;
use TDF\ExpertSenderApi\Services\Templates;

/**
 * Class ExpertSenderClient
 *
 * @author Isaac Rozas García <isaac.rozgar@gmail.com>
 */
class ExpertSenderClient
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct($apiKey = null, $domain = null)
    {
        $this->apiKey = $apiKey;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param string $method
     * @param string $content
     *
     * @return ApiResponse
     * @throws \RuntimeException
     */
    public function sendRequest($url, array $parameters, $method = ApiRequest::REQUEST_GET, $content = '')
    {
        if (($method === ApiRequest::REQUEST_GET || $method === ApiRequest::REQUEST_DELETE) &&
            (!isset($parameters['apiKey']) || !$parameters['apiKey'])
        ) {
            throw new \RuntimeException(
                sprintf('API Key not set.')
            );
        }

        $apiRequest = $this->createRequest($url, $parameters, $method, $content);

        return $apiRequest->send();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function createRequest($url, array $parameters, $method, $content)
    {
        return new ApiRequest($url, $parameters, $method, $content);
    }

    /**
     * @return SignalSpamStatistics
     */
    public function signalSpamStatistics()
    {
        return new SignalSpamStatistics($this, $this->domain);
    }

    /**
     * @return Messages
     */
    public function messages()
    {
        return new Messages($this, $this->domain);
    }

    /**
     * @return Segmentations
     */
    public function segmentations()
    {
        return new Segmentations($this, $this->domain);
    }

    /**
     * @return Templates
     */
    public function templates()
    {
        return new Templates($this, $this->domain);
    }

    /**
     * @return Server
     */
    public function server()
    {
        return new Server($this, $this->domain);
    }
}
