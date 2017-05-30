<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Services;

use DateTime;
use DateTimeImmutable;
use PHPUnit_Framework_MockObject_MockObject;
use ExpertSenderFr\ExpertSenderApi\ApiRequest;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;
use ExpertSenderFr\ExpertSenderApi\Services\Server;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    /**
     * @test
     */
    public function tryGetTime()
    {
        /** @var ExpertSenderClient|PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(ExpertSenderClient::class);

        $response = new ApiResponse('<ApiResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <Data>2013-04-24T10:33:09.4338472Z</Data>
</ApiResponse>', 200, []);

        $client->expects($this->once())
            ->method('sendRequest')
            ->with('https://example.com/Api/Time', ['apiKey' => '1234'], ApiRequest::REQUEST_GET)
            ->willReturn($response)
        ;

        $server = new Server($client, 'https://example.com/');
        $time = $server->getTime([
            'domain' => 'https://example.com/',
            'api_key' => '1234',
        ]);

        $this->assertInstanceOf(DateTimeImmutable::class, $time);
        $this->assertSame('2013-04-24T10:33:09.433847Z', $time->format('Y-m-d\TH:i:s.uT'));
    }
}
