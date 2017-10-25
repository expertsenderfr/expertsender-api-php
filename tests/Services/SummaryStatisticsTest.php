<?php

namespace Services;

use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\Services\SummaryStatistics;
use ExpertSenderFr\ExpertSenderApi\Test\FakeApiRequest;
use ExpertSenderFr\ExpertSenderApi\Test\TestClient;
use PHPUnit\Framework\TestCase;

class SummaryStatisticsTest extends TestCase
{
    public function testGet()
    {
        $responseBody = <<<EOF
<ApiResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <Data>
        <SummaryStatistics>
            <SummaryStatistic>
                <IsSummaryRow>true</IsSummaryRow>
                <Sent>20644833</Sent>
                <Bounced>1337836</Bounced>
                <Delivered>19306997</Delivered>
                <Opens>1284158</Opens>
                <UniqueOpens>995388</UniqueOpens>
                <Clicks>105489</Clicks>
                <UniqueClicks>87495</UniqueClicks>
                <Clickers>67939</Clickers>
                <Complaints>361</Complaints>
                <Unsubscribes>27509</Unsubscribes>
                <Goals>5</Goals>
                <GoalsValue>4</GoalsValue>
            </SummaryStatistic>
        </SummaryStatistics>
    </Data>
</ApiResponse>
EOF;

        $response = new ApiResponse($responseBody, 200, []);

        $request = new FakeApiRequest();
        $request->setResponse($response);

        $client = new TestClient();
        $client->setNextRequest($request);

        $service = new SummaryStatistics($client, 'http://example.com');

        $summaryStatistics = $service->getAll([], ['api_key' => 'fake']);

        $this->assertInternalType('array', $summaryStatistics);
        $this->assertSame(true, $summaryStatistics[0]['isSummaryRow']);
        $this->assertSame(null, $summaryStatistics[0]['domainFamily']);
        $this->assertSame(20644833, $summaryStatistics[0]['sent']);
        $this->assertSame(1337836, $summaryStatistics[0]['bounced']);
        $this->assertSame(19306997, $summaryStatistics[0]['delivered']);
        $this->assertSame(1284158, $summaryStatistics[0]['opens']);
        $this->assertSame(995388, $summaryStatistics[0]['uniqueOpens']);
        $this->assertSame(105489, $summaryStatistics[0]['clicks']);
        $this->assertSame(87495, $summaryStatistics[0]['uniqueClicks']);
        $this->assertSame(67939, $summaryStatistics[0]['clickers']);
        $this->assertSame(361, $summaryStatistics[0]['complaints']);
        $this->assertSame(27509, $summaryStatistics[0]['unsubscribes']);
        $this->assertSame(5, $summaryStatistics[0]['goals']);
        $this->assertSame(4, $summaryStatistics[0]['goalsValue']);
    }

    public function testGetGroupingByDomainFamily()
    {
        $responseBody = <<<EOF
<ApiResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <Data>
        <SummaryStatistics>
            <SummaryStatistic>
                <IsSummaryRow>true</IsSummaryRow>
                <Sent>20647098</Sent>
                <Bounced>1337843</Bounced>
                <Delivered>19309255</Delivered>
                <Opens>1284395</Opens>
                <UniqueOpens>995469</UniqueOpens>
                <Clicks>105498</Clicks>
                <UniqueClicks>87499</UniqueClicks>
                <Clickers>67941</Clickers>
                <Complaints>361</Complaints>
                <Unsubscribes>27510</Unsubscribes>
                <Goals>5</Goals>
                <GoalsValue>4</GoalsValue>
            </SummaryStatistic>
            <SummaryStatistic>
                <DomainFamily>Yahoo</DomainFamily>
                <Sent>1001761</Sent>
                <Bounced>74423</Bounced>
                <Delivered>927338</Delivered>
                <Opens>55906</Opens>
                <UniqueOpens>34933</UniqueOpens>
                <Clicks>4780</Clicks>
                <UniqueClicks>2732</UniqueClicks>
                <Clickers>2180</Clickers>
                <Complaints>285</Complaints>
                <Unsubscribes>618</Unsubscribes>
                <Goals>2</Goals>
                <GoalsValue>1</GoalsValue>
            </SummaryStatistic>
            <SummaryStatistic>
                <DomainFamily>La Poste</DomainFamily>
                <Sent>975934</Sent>
                <Bounced>8563</Bounced>
                <Delivered>967371</Delivered>
                <Opens>54403</Opens>
                <UniqueOpens>39910</UniqueOpens>
                <Clicks>6051</Clicks>
                <UniqueClicks>3557</UniqueClicks>
                <Clickers>3149</Clickers>
                <Complaints>2</Complaints>
                <Unsubscribes>2050</Unsubscribes>
                <Goals>0</Goals>
                <GoalsValue>0</GoalsValue>
            </SummaryStatistic>
        </SummaryStatistics>
    </Data>
</ApiResponse>
EOF;

        $response = new ApiResponse($responseBody, 200, []);

        $request = new FakeApiRequest();
        $request->setResponse($response);

        $client = new TestClient();
        $client->setNextRequest($request);

        $service = new SummaryStatistics($client, 'http://example.com');

        $summaryStatistics = $service->getAll([], ['api_key' => 'fake']);

        // Test for the summary row :
        $this->assertInternalType('array', $summaryStatistics);
        $this->assertSame(true, $summaryStatistics[0]['isSummaryRow']);
        $this->assertSame(null, $summaryStatistics[0]['domainFamily']);
        $this->assertSame(20647098, $summaryStatistics[0]['sent']);
        $this->assertSame(1337843, $summaryStatistics[0]['bounced']);
        $this->assertSame(19309255, $summaryStatistics[0]['delivered']);
        $this->assertSame(1284395, $summaryStatistics[0]['opens']);
        $this->assertSame(995469, $summaryStatistics[0]['uniqueOpens']);
        $this->assertSame(105498, $summaryStatistics[0]['clicks']);
        $this->assertSame(87499, $summaryStatistics[0]['uniqueClicks']);
        $this->assertSame(67941, $summaryStatistics[0]['clickers']);
        $this->assertSame(361, $summaryStatistics[0]['complaints']);
        $this->assertSame(27510, $summaryStatistics[0]['unsubscribes']);
        $this->assertSame(5, $summaryStatistics[0]['goals']);
        $this->assertSame(4, $summaryStatistics[0]['goalsValue']);

        // Test for other row (with DomainFamily) :
        $this->assertInternalType('array', $summaryStatistics);
        $this->assertSame(false, $summaryStatistics[1]['isSummaryRow']);
        $this->assertSame('Yahoo', $summaryStatistics[1]['domainFamily']);
        $this->assertSame(1001761, $summaryStatistics[1]['sent']);
        $this->assertSame(74423, $summaryStatistics[1]['bounced']);
        $this->assertSame(927338, $summaryStatistics[1]['delivered']);
        $this->assertSame(55906, $summaryStatistics[1]['opens']);
        $this->assertSame(34933, $summaryStatistics[1]['uniqueOpens']);
        $this->assertSame(4780, $summaryStatistics[1]['clicks']);
        $this->assertSame(2732, $summaryStatistics[1]['uniqueClicks']);
        $this->assertSame(2180, $summaryStatistics[1]['clickers']);
        $this->assertSame(285, $summaryStatistics[1]['complaints']);
        $this->assertSame(618, $summaryStatistics[1]['unsubscribes']);
        $this->assertSame(2, $summaryStatistics[1]['goals']);
        $this->assertSame(1, $summaryStatistics[1]['goalsValue']);

    }
}

