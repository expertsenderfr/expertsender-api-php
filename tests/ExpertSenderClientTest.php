<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use ExpertSenderFr\ExpertSenderApi\ApiRequest;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;
use ExpertSenderFr\ExpertSenderApi\Services\Messages;
use ExpertSenderFr\ExpertSenderApi\Services\SignalSpamStatistics;

class ExpertSenderClientTest extends TestCase
{
    /**
     * @test
     */
    public function createsInstanceOfMessages()
    {
        $client = new ExpertSenderClient('FAKE_KEY', 'http://api.example.com');

        $service = $client->messages();

        $this->assertInstanceOf(Messages::class, $service);
        $this->assertAttributeSame($client, 'client', $service);
        $this->assertAttributeEquals('http://api.example.com', 'domain', $service);
    }

    /**
     * @test
     */
    public function createInstanceOfSignalSpamStatistics()
    {
        $client = new ExpertSenderClient('FAKE_KEY', 'http://api.example.com');

        $service = $client->signalSpamStatistics();

        $this->assertInstanceOf(SignalSpamStatistics::class, $service);
        $this->assertAttributeSame($client, 'client', $service);
        $this->assertAttributeEquals('http://api.example.com', 'domain', $service);
    }

    /**
     * @test
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage API Key not set.
     */
    public function throwsExceptionIfRequestIsGetRequestAndThereIsNoApiKey()
    {
        $client = new ExpertSenderClient(null, 'http://api.example.com');
        $client->sendRequest('http://api.example/hello', [], ApiRequest::REQUEST_GET, '');
    }

    /**
     * @test
     */
    public function sendingARequestReturnsAnApiResponse()
    {
        $request = $this->getMockBuilder(ApiRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['send'])
            ->getMock();

        $request->expects($this->once())
            ->method('send')
            ->willReturn(new ApiResponse('Fake response', 200, []));

        $client = new TestClient('FAKE_KEY', 'http://api.example.com');
        $client->setNextRequest($request);
        $response = $client->sendRequest(
            'http://api.example.com/hello',
            ['apiKey' => 'FAKE_KEY'],
            ApiRequest::REQUEST_GET
        );

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals('Fake response', $response->body);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function canGetTheApiKeySet()
    {
        $client = new ExpertSenderClient('FAKE_KEY', 'http://api.example.com');
        $this->assertEquals('FAKE_KEY', $client->getApiKey());
    }
}

class TestClient extends ExpertSenderClient
{
    protected $nextRequest;

    protected function createRequest($url, array $parameters, $method, $content)
    {
        return $this->nextRequest;
    }

    public function setNextRequest(ApiRequest $request)
    {
        $this->nextRequest = $request;
    }
}
