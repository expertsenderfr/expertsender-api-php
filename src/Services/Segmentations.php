<?php

namespace TDF\ExpertSenderApi\Services;

use TDF\ExpertSenderApi\ApiResponse;
use Symfony\Component\DomCrawler\Crawler;
use TDF\ExpertSenderApi\Model\Segment;

class Segmentations extends ApiService
{

    const SERVICE_URL = 'Api/Segments';

    /**
     * Gets the list of the segmentation for the database
     *
     * @param array $opts
     *
     * @return array
     * @throws \RuntimeException
     */
    public function get(array $opts = [])
    {
        $response = $this->doAll([], $opts);

        if($response->getStatusCode() !== 200){
            throw new \RuntimeException($response->body);
        }

        return $this->parseGetAllResponse($response);
    }

    public function parseGetAllResponse(ApiResponse $response)
    {
        $crawler = $response->getCrawler();

        $segments = [];

        $crawler->filterXPath('//Segments/Segment')->each(function (Crawler $node, $i) use (&$segments) {
            $segments[] =  new Segment(
                (int)$node->filterXPath('//Id')->text(),
                $node->filterXPath('//Name')->text()
            );
        });

        return $segments;
    }
}