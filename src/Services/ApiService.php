<?php

namespace TDF\ExpertSenderApi\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;
use TDF\ExpertSenderApi\ApiRequest;
use TDF\ExpertSenderApi\ApiResponse;
use TDF\ExpertSenderApi\ExpertSenderClient;
use TDF\ExpertSenderApi\SerializerFactory;

abstract class ApiService
{
    const SERVICE_URL = '';

    /**
     * @var ExpertSenderClient
     */
    protected $client;

    protected $logger;

    /**
     * ApiService constructor.
     *
     * @param ExpertSenderClient $client
     * @param                    $domain
     */
    public function __construct(ExpertSenderClient $client, $domain)
    {
        $this->client = $client;
        $this->domain = $domain;
        $this->logger = $client->getLogger();
    }

    protected function doAll(array $parameters = [], array $opts = [])
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $opts = $optionsResolver->resolve($opts);

        if (empty($opts['domain'])) {
            throw new \RuntimeException('The domain cannot be an empty string.');
        }

        $parametersResolver = new OptionsResolver();
        $this->configureAllParameters($parametersResolver);
        $this->configureCommonParameters($parametersResolver, $opts);
        $parameters = $parametersResolver->resolve($parameters);

        $response = $this->client->sendRequest(
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'],
            $parameters,
            ApiRequest::REQUEST_GET
        );

        $this->log(
            'Request sent to Expert Sender',
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'],
            $response,
            $parameters
        );

        return $response;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('api_key', $this->client->getApiKey());
        $resolver->setDefault('domain', $this->domain);
        $resolver->setAllowedTypes('domain', 'string');
        $resolver->setDefault('endpoint', static::SERVICE_URL);
    }

    protected function configureAllParameters(OptionsResolver $resolver, array $opts = [])
    {
    }

    protected function configureCommonParameters(OptionsResolver $resolver, array $opts = [])
    {
        $resolver->setDefault('apiKey', $opts['api_key']);
    }

    protected function doGet($resourceId, array $parameters = [], array $opts = [])
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $opts = $optionsResolver->resolve($opts);

        $parametersResolver = new OptionsResolver();
        $this->configureAllParameters($parametersResolver);
        $this->configureCommonParameters($parametersResolver, $opts);
        $parameters = $parametersResolver->resolve($parameters);

        $response = $this->client->sendRequest(
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'] . '/' . $resourceId,
            $parameters,
            ApiRequest::REQUEST_GET
        );

        $this->log(
            'Request sent to Expert Sender',
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'] . '/' . $resourceId,
            $response,
            $parameters
        );

        return $response;
    }

    protected function doCreate(array $content, array $opts = [])
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $opts = $optionsResolver->resolve($opts);

        $apiKey = isset($opts['api_key']) ? $opts['api_key'] : $this->client->getApiKey();

        if (!$apiKey) {
            throw new \RuntimeException(
                sprintf('API Key not set.')
            );
        }

        $requestBody = $this->buildXml($apiKey, $content);
        $requestBody = array_merge($requestBody, $content);

        $serializer = SerializerFactory::createXmlSerializer();

        $serializedRequestBody = $serializer->serialize($requestBody, 'xml');

        $response = $this->client->sendRequest(
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'],
            [],
            ApiRequest::REQUEST_POST,
            $serializedRequestBody
        );

        $this->log(
            'Request sent to Expert Sender',
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'],
            $response,
            [],
            $serializedRequestBody
        );

        return $response;
    }

    private function buildXml($apiKey, array $data)
    {
        return [
//            'ApiRequest' => [
            '@xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            '@xmlns:xs' => 'http://www.w3.org/2001/XMLSchema',
            'ApiKey' => $apiKey,
//                'Data' => $data
//            ]
        ];
    }

    protected function doUpdate($resourceId, array $content, array $opts = [])
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $opts = $optionsResolver->resolve($opts);

        $apiKey = isset($opts['api_key']) ? $opts['api_key'] : $this->client->getApiKey();

        if (!$apiKey) {
            throw new \RuntimeException(
                sprintf('API Key not set.')
            );
        }

        $requestBody = $this->buildXml($apiKey, $content);
        $requestBody = array_merge($requestBody, $content);

        $serializer = SerializerFactory::createXmlSerializer();

        $serializedRequestBody = $serializer->serialize($requestBody, 'xml');

        $response = $this->client->sendRequest(
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'] . '/' . $resourceId,
            [],
            ApiRequest::REQUEST_PUT,
            $serializedRequestBody
        );

        $this->log(
            'Request sent to Expert Sender',
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'] . '/' . $resourceId,
            $response,
            [],
            $serializedRequestBody
        );


        return $response;
    }

    protected function doDelete($resourceId, array $parameters = [], array $opts = [])
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $opts = $optionsResolver->resolve($opts);

        if (empty($opts['domain'])) {
            throw new \RuntimeException('The domain cannot be an empty string.');
        }

        $parametersResolver = new OptionsResolver();
        $this->configureDeleteParameters($parametersResolver);
        $this->configureCommonParameters($parametersResolver, $opts);
        $parameters = $parametersResolver->resolve($parameters);

        $response = $this->client->sendRequest(
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'] . '/' . $resourceId,
            $parameters,
            ApiRequest::REQUEST_DELETE
        );

        $this->log(
            'Request sent to Expert Sender',
            rtrim($opts['domain'], '/') . '/' . $opts['endpoint'] . '/' . $resourceId,
            $response,
            $parameters
        );

        return $response;
    }

    protected function configureDeleteParameters(OptionsResolver $resolver, array $opts = [])
    {
    }

    protected function configureGetParameters(OptionsResolver $resolver, array $opts = [])
    {
    }

    protected function configureSaveParameters(OptionsResolver $resolver, array $opts = [])
    {
    }

    private function log($message, $url, ApiResponse $response, $requestParameters, $requestBody = null)
    {
        if ($this->logger) {
            $context = [
                'endpoint' => $url,
                'request_parameters' => $requestParameters,
                'status_code' => $response->getStatusCode(),
                'response_body' => (string)$response,
            ];

            if ($requestBody !== null) {
                $context['request_body'] = $requestBody;
            }

            if ($response->getStatusCode() >= 400) {
                $this->logger->error($message, $context);
            } else {
                $this->logger->debug($message, $context);
            }
        }
    }
}
