<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use ExpertSenderFr\ExpertSenderApi\Model\RemoteList;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Lists extends ApiService
{
    const SERVICE_URL = 'Api/Lists';

    public function get($type, array $opts)
    {
        $response = $this->doAll(['seedLists' => $type === 'bat'], $opts);


        $lists = [];

        $crawler = $response->getCrawler();

        $crawler->filterXPath('//Lists/List')->each(function (Crawler $node, $i) use (&$lists, $type) {
            $lists[] =  new RemoteList(
                (int)$node->filterXPath('//Id')->text(),
                $node->filterXPath('//Name')->text(),
                $type
            );
        });

        return $lists;
    }

    protected function configureAllParameters(OptionsResolver $resolver, array $opts = [])
    {
        $resolver->setDefined(['seedLists']);
    }
}
