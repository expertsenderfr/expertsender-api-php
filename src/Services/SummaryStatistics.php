<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DomCrawler\Crawler;

class SummaryStatistics extends ApiService
{
    const SERVICE_URL = 'Api/SummaryStatistics';

    public function getAll(array $parameters, $opts)
    {
        $response = $this->doAll($parameters, $opts);
        $crawler = $response->getCrawler();

        $result = [];

        if ($crawler->filterXPath('//Data/SummaryStatistics')->count() > 0) {
            $crawler->filterXPath('//Data/SummaryStatistics/SummaryStatistic')->each(function (Crawler $node, $i) use (&$result) {

                $result[] = [
                    'isSummaryRow' => ($node->filterXPath('//IsSummaryRow')->count() > 0) ? true : false,
                    'domainFamily' => ($node->filterXPath('//DomainFamily')->count() > 0) ? $node->filterXPath('//DomainFamily')->text() : null,
                    'sent' => (int)$node->filterXPath('//Sent')->text(),
                    'bounced' => (int)$node->filterXPath('//Bounced')->text(),
                    'delivered' => (int)$node->filterXPath('//Delivered')->text(),
                    'opens' => (int)$node->filterXPath('//Opens')->text(),
                    'uniqueOpens' => (int)$node->filterXPath('//UniqueOpens')->text(),
                    'clicks' => (int)$node->filterXPath('//Clicks')->text(),
                    'uniqueClicks' => (int)$node->filterXPath('//UniqueClicks')->text(),
                    'clickers' => (int)$node->filterXPath('//Clickers')->text(),
                    'complaints' => (int)$node->filterXPath('//Complaints')->text(),
                    'unsubscribes' => (int)$node->filterXPath('//Unsubscribes')->text(),
                    'goals' => (int)$node->filterXPath('//Goals')->text(),
                    'goalsValue' => (int)$node->filterXPath('//GoalsValue')->text()
                ];

            });
        }
        return $result;
    }

    protected function configureAllParameters(OptionsResolver $resolver, array $opts = [])
    {
        $resolver->setDefined([
            'startDate',
            'endDate',
            'grouping'
        ]);
    }
}
