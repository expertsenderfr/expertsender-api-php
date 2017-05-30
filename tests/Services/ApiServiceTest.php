<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;
use ExpertSenderFr\ExpertSenderApi\ApiRequest;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;
use ExpertSenderFr\ExpertSenderApi\Services\ApiService;

class ApiServiceTest extends TestCase
{
    /**
     * @test
     */
    public function tryDoAll()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with('http://example.com/', ['apiKey' => '12345'], ApiRequest::REQUEST_GET)
            ->willReturn($this->createMock(ApiResponse::class))
        ;

        $service = new FakeApiService($client);

        $service->all([], ['domain' => 'http://example.com', 'api_key' => '12345']);
    }



    /**
     * @test
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The domain cannot be an empty string.
     */
    public function doAllThrowsExceptionWhenNoDomainIsSpecified()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $service = new FakeApiService($client);
        $service->all([], ['api_key' => '12345', 'endpoint' => 'resource']);
    }

    /**
     * @test
     */
    public function tryDoGet()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with('http://example.com/resource/98372', ['apiKey' => '12345'], ApiRequest::REQUEST_GET)
            ->willReturn($this->createMock(ApiResponse::class))
        ;

        $service = new FakeApiService($client);

        $service->get(98372, [], ['domain' => 'http://example.com', 'api_key' => '12345', 'endpoint' => 'resource']);
    }

    /**
     * @test
     */
    public function tryDoCreate()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $expectedRequestContent = <<<EOF
<?xml version="1.0"?>
<ApiRequest xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema"><ApiKey>12345</ApiKey><Data>This is a Test!</Data></ApiRequest>

EOF;

        $client->expects($this->once())
            ->method('sendRequest')
            ->with(
                'http://example.com/resource',
                [],
                ApiRequest::REQUEST_POST,
                $expectedRequestContent
            )
            ->willReturn($this->createMock(ApiResponse::class))
        ;

        $service = new FakeApiService($client);

        $service->create(['Data' => 'This is a Test!'], ['domain' => 'http://example.com', 'api_key' => '12345', 'endpoint' => 'resource']);
    }

    /**
     * @test
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage API Key not set.
     */
    public function doCreateThrowsExceptionIfThereIsNoApiKey()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $service = new FakeApiService($client);

        $service->create(['Data' => 'This is a Test!'], ['domain' => 'http://example.com', 'endpoint' => 'resource']);
    }

    /**
     * @test
     */
    public function tryDoUpdate()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $expectedRequestContent = <<<EOF
<?xml version="1.0"?>
<ApiRequest xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema"><ApiKey>12345</ApiKey><Data>This is a Test!</Data></ApiRequest>

EOF;

        $client->expects($this->once())
            ->method('sendRequest')
            ->with(
                'http://example.com/resource/98736',
                [],
                ApiRequest::REQUEST_PUT,
                $expectedRequestContent
            )
            ->willReturn($this->createMock(ApiResponse::class))
        ;

        $service = new FakeApiService($client);

        $service->update(98736, ['Data' => 'This is a Test!'], ['domain' => 'http://example.com', 'api_key' => '12345', 'endpoint' => 'resource']);
    }

    /**
     * @test
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage API Key not set.
     */
    public function doUpdateThrowsExceptionIfThereIsNoApiKey()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $service = new FakeApiService($client);

        $service->update(98736, ['Data' => 'This is a Test!'], ['domain' => 'http://example.com', 'endpoint' => 'resource']);
    }

    /**
     * @test
     */
    public function tryDoDelete()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with('http://example.com/resource/98372', ['apiKey' => '12345'], ApiRequest::REQUEST_DELETE)
            ->willReturn($this->createMock(ApiResponse::class))
        ;

        $service = new FakeApiService($client);

        $service->delete(98372, [], ['domain' => 'http://example.com', 'api_key' => '12345', 'endpoint' => 'resource']);
    }

    /**
     * @test
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The domain cannot be an empty string.
     */
    public function doDeleteThrowsExceptionWhenNoDomainIsSpecified()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $service = new FakeApiService($client);

        $service->delete(98372, [], ['api_key' => '12345', 'endpoint' => 'resource']);
    }

    /**
     * @test
     */
    public function logsResponsesIfALoggerIsSet()
    {
        $response = $this->createMock(ApiResponse::class);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('debug');

        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);
        $client->method('getLogger')->willReturn($logger);
        $client->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response)
        ;

        $service = new FakeApiService($client);

        $service->delete(98372, [], ['domain' => 'http://example.com', 'api_key' => '12345', 'endpoint' => 'resource']);
    }

    /**
     * @test
     */
    public function logsResponsesAsErrorIfALoggerIsSetAndStatusCodeIsGreaterThan399()
    {
        $response = $this->createMock(ApiResponse::class);
        $response->method('getStatusCode')->willReturn(400);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error');

        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);
        $client->method('getLogger')->willReturn($logger);
        $client->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response)
        ;

        $service = new FakeApiService($client);

        $service->delete(98372, [], ['domain' => 'http://example.com', 'api_key' => '12345', 'endpoint' => 'resource']);
    }
}

class FakeApiService extends ApiService
{
    public function __construct($client)
    {
        parent::__construct($client, '');
    }

    public function all(array $parameters = [], array $options = [])
    {
        return $this->doAll($parameters, $options);
    }

    public function get($resourceId, array $parameters = [], array $options = [])
    {
        return $this->doGet($resourceId, $parameters, $options);
    }

    public function create(array $content, array $options = [])
    {
        return $this->doCreate($content, $options);
    }

    public function update($resourceId, array $content, array $options = [])
    {
        return $this->doUpdate($resourceId, $content, $options);
    }

    public function delete($resourceId, array $parameters = [], array $options = [])
    {
        return $this->doDelete($resourceId, $parameters, $options);
    }
}
