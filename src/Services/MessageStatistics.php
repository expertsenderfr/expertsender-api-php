<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageStatistics extends ApiService
{
    const SERVICE_URL = 'Api/MessageStatistics';

    public function getAll($id, array $parameters, array $opts)
    {
        $response = $this->doGet($id, $parameters, $opts);
        $crawler = $response->getCrawler();

        $result = [
            'externalId' => $id,
            'sent' => $crawler->filterXPath('//Sent')->text(),
            'bounced' => $crawler->filterXPath('//Bounced')->text(),
            'delivered' => $crawler->filterXPath('//Delivered')->text(),
            'opens' => $crawler->filterXPath('//Opens')->text(),
            'uniqueOpens' => $crawler->filterXPath('//UniqueOpens')->text(),
            'clicks' => $crawler->filterXPath('//Clicks')->text(),
            'uniqueClicks' => $crawler->filterXPath('//UniqueClicks')->text(),
            'clickers' => $crawler->filterXPath('//Clickers')->text(),
            'complaints' => $crawler->filterXPath('//Complaints')->text(),
            'unsubscribes' => $crawler->filterXPath('//Unsubscribes')->text(),
        ];

        return $result;
    }

    protected function configureGetParameters(OptionsResolver $resolver, array $opts = [])
    {
        $resolver->setDefined([
            'startDate',
            'endDate'
        ]);
    }
}
