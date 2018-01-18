<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Services;

use DateTimeImmutable;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;
use ExpertSenderFr\ExpertSenderApi\Test\FakeApiRequest;
use ExpertSenderFr\ExpertSenderApi\Test\TestClient;
use PHPUnit\Framework\TestCase;

final class SegmentsCountTest extends TestCase
{
    public function testCanFireASegmentRecount()
    {
        $responseBody = <<<EOF
<ApiResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <Data>
      <Size>3384047</Size>
      <CountDate>2016-12-08T12:37:23.0784944Z</CountDate>
   </Data>
</ApiResponse>
EOF;
        $response = new ApiResponse($responseBody, 200, []);
        $request = new FakeApiRequest();
        $request->setResponse($response);

        $client = new TestClient('<YOUR_API_KEY_HERE>', 'https://api2.esv2.com');
        $client->setNextRequest($request);
        $service = $client->getSegmentsCount();

        $response = $service->get(1);

        $this->assertSame(3384047, $response['size']);
        $this->assertInstanceOf(DateTimeImmutable::class, $response['count_date']);
        $this->assertSame(
            '2016-12-08 12:37:23',
            $response['count_date']->format('Y-m-d H:i:s')
        );
    }
}
