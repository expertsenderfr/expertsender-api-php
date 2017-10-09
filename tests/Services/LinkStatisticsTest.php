<?php

namespace Services;

use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\Model\RemoteList;
use ExpertSenderFr\ExpertSenderApi\Services\LinkStatistics;
use ExpertSenderFr\ExpertSenderApi\Test\FakeApiRequest;
use ExpertSenderFr\ExpertSenderApi\Test\TestClient;
use PHPUnit\Framework\TestCase;

class LinkStatisticsTest extends TestCase
{
    public function testGet()
    {
        $responseBody = <<<EOF
<ApiResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <Data>
     <LinkStatistics>
       <LinkStatistic>
         <Url>http://www.msn.com</Url>
         <Clicks>5</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
       <LinkStatistic>
         <Url>http://www.google.com</Url>
         <Clicks>2</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
       <LinkStatistic>
         <Url>http://www.cnn.com</Url>
         <Clicks>5</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
     </LinkStatistics>
   </Data>
 </ApiResponse>
EOF;

        $response = new ApiResponse($responseBody, 200, []);

        $request = new FakeApiRequest();
        $request->setResponse($response);

        $client = new TestClient();
        $client->setNextRequest($request);

        $service = new LinkStatistics($client, 'http://example.com');

        $linkStatistics = $service->getAll(1234, [], ['api_key' => 'fake']);

        $this->assertInternalType('array', $linkStatistics);

        $this->assertSame(1234, $linkStatistics['externalId']);
        $this->assertSame(12, $linkStatistics['totalClicks']);
        $this->assertSame(3, $linkStatistics['uniqueClicks']);
    }
    public function testGetDoesNotIncludeBlacklistedLinks()
    {
        $responseBody = <<<EOF
<ApiResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
   <Data>
     <LinkStatistics>
       <LinkStatistic>
         <Url>http://www.msn.com</Url>
         <Clicks>5</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
       <LinkStatistic>
         <Url>http://www.google.com/conditions.php</Url>
         <Clicks>2</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
       <LinkStatistic>
         <Url>link_viewinbrowser</Url>
         <Clicks>2</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
       <LinkStatistic>
         <Url>mailto:desinscription@example.com</Url>
         <Clicks>2</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
       <LinkStatistic>
         <Url>http://www.cnn.com</Url>
         <Clicks>5</Clicks>
         <UniqueClicks>1</UniqueClicks>
       </LinkStatistic>
     </LinkStatistics>
   </Data>
 </ApiResponse>
EOF;

        $response = new ApiResponse($responseBody, 200, []);

        $request = new FakeApiRequest();
        $request->setResponse($response);

        $client = new TestClient();
        $client->setNextRequest($request);

        $service = new LinkStatistics($client, 'http://example.com');

        $linkStatistics = $service->getAll(1234, [], ['api_key' => 'fake']);

        $this->assertInternalType('array', $linkStatistics);

        $this->assertSame(1234, $linkStatistics['externalId']);
        $this->assertSame(10, $linkStatistics['totalClicks']);
        $this->assertSame(2, $linkStatistics['uniqueClicks']);
    }
}

