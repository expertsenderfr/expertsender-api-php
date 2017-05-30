<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests;

use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class ApiResponseTest extends TestCase
{
    /**
     * @test
     */
    public function canGetACrawlerForTheResponse()
    {
        $xml = <<<EOF
<ApiResponse
  xmlns:xsd="http://www.w3.org/2001/XMLSchema"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
>
   <Data>1498</Data>
 </ApiResponse>
EOF;


        $response = new ApiResponse($xml, 200, []);

        $this->assertInstanceOf(Crawler::class, $response->getCrawler());
    }

    /**
     * @test
     */
    public function crawlerContainsExpectedXml()
    {
        $xml = '<?xml version="1.0" ?>
<ApiResponse
  xmlns:xsd="http://www.w3.org/2001/XMLSchema"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
>
   <Data>1498</Data>
 </ApiResponse>';

        $response = new ApiResponse($xml, 200, []);
        $crawler = $response->getCrawler();

        $this->assertEquals('ApiResponse',$crawler->nodeName());
        $this->assertCount(1, $crawler->filterXPath('//ApiResponse/Data'));
        $this->assertEquals(1498, $crawler->filterXPath('//ApiResponse/Data')->text());
    }
}
