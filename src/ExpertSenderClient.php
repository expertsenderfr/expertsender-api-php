<?php

namespace ExpertSenderFr\ExpertSenderApi;

use ExpertSenderFr\ExpertSenderApi\Model\Template;
use ExpertSenderFr\ExpertSenderApi\Services\Lists;
use ExpertSenderFr\ExpertSenderApi\Services\Messages;
use ExpertSenderFr\ExpertSenderApi\Services\Segmentations;
use ExpertSenderFr\ExpertSenderApi\Services\SegmentsCount;
use ExpertSenderFr\ExpertSenderApi\Services\Server;
use ExpertSenderFr\ExpertSenderApi\Services\SignalSpamStatistics;
use ExpertSenderFr\ExpertSenderApi\Services\Subscribers;
use ExpertSenderFr\ExpertSenderApi\Services\SummaryStatistics;
use ExpertSenderFr\ExpertSenderApi\Services\Templates;
use ExpertSenderFr\ExpertSenderApi\Services\LinkStatistics;

/**
 * Class ExpertSenderClient
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

        /** @var ApiResponse $response */
        $response = null;
        try {
            $response = $apiRequest->send();
            return $response;
        } catch (\Exception $e) {
            $context = [
                'request' => [
                    'endpoint' => $url,
                    'method' => $method,
                    'parameters' => $parameters,
                    'content' => $content
                ],
                'response' => null
            ];

            if ($response !== null) {
                $context['response'] = [
                    'status' => $response->getStatusCode(),
                    'body' => $response->__toString(),
                ];
            }
            $this->logger->log('info', $e->getMessage(), $context);

            throw $e;
        }

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

    /**
     * @return Subscribers
     */
    public function subscribers()
    {
        return new Subscribers($this, $this->domain);
    }

    /**
     * @return Lists
     */
    public function lists()
    {
        return new Lists($this, $this->domain);
    }

    /**
     * @return LinkStatistics
     */
    public function linkStatistics()
    {
        return new LinkStatistics($this, $this->domain);
    }

    /**
     * @return SummaryStatistics
     */
    public function summaryStatistics()
    {
        return new SummaryStatistics($this, $this->domain);
    }

    /**
     * return SegmentsCount
     */
    public function getSegmentsCount()
    {
        return new SegmentsCount($this, $this->domain);
    }
}
