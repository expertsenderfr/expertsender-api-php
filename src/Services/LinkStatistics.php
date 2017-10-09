<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DomCrawler\Crawler;

class LinkStatistics extends ApiService
{
    const SERVICE_URL = 'Api/LinkStatistics';

    private static $linksBlacklist = [
        'link_viewinbrowser',
        'mailto:desinscription',
        'conditions.php'
    ];

    public function getAll($id, array $parameters, array $opts)
    {
        $response = $this->doGet($id, $parameters, $opts);
        $crawler = $response->getCrawler();

        $result = [
            'externalId' => $id,
            'totalClicks' => 0,
            'uniqueClicks' => 0,
        ];

        if ($crawler->filterXPath('//Data/LinkStatistics')->count() > 0) {
            $crawler->filterXPath('//Data/LinkStatistics/LinkStatistic')->each(function (Crawler $node, $i) use (&$result) {

                if ( $this->validOrNot($node) ) {
                    $result['totalClicks'] += (int)$node->filterXPath('//Clicks')->text();
                    $result['uniqueClicks'] += (int)$node->filterXPath('//UniqueClicks')->text();
                }
            });
        }
        return $result;
    }

    protected function configureGetParameters(OptionsResolver $resolver, array $opts = [])
    {
        $resolver->setDefined([
            'startDate',
            'endDate'
        ]);
    }

    protected function validOrNot($node)
    {
        $url = $node->filterXPath('//Url')->text();

        foreach (static::$linksBlacklist as $link) {
            if(preg_match('/'.$link.'/',$url)) {
                return false;
            }
        }

        return true;
    }
}