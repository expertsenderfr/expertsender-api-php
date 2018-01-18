<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use DateTimeImmutable;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;

final class SegmentsCount extends ApiService
{
    const SERVICE_URL = 'Api/GetSegmentSize';

    public function get($segmentId, array $opts = [])
    {
        $response = $this->doGet($segmentId, [], $opts);

        return $this->parseGetResponse($response);
    }

    private function parseGetResponse(ApiResponse $response)
    {
        $crawler = $response->getCrawler();

        return [
            'size' => (int)$crawler->filterXPath('//Data/Size')->text(),
            'count_date' => new DateTimeImmutable($crawler->filterXPath('//Data/CountDate')->text())
        ];
    }
}
