<?php

namespace TDF\ExpertSenderApi\Services;

use Symfony\Component\DomCrawler\Crawler;
use TDF\ExpertSenderApi\Model\Template;

class Templates extends ApiService
{
    const SERVICE_URL = 'Api/Templates';

    public function get($type = null, array $opts = [])
    {
        $response =  $this->doAll([], $opts);

        $crawler = $response->getCrawler();

        $templates = [];
        if ($crawler->filterXPath('//Data/Templates')->count() > 0) {
            $crawler->filterXPath('//Data/Templates/Template')->each(function (Crawler $node, $i) use (&$templates) {
                $templates[] = new Template(
                    (int)$node->filterXPath('//Id')->text(),
                    $node->filterXPath('//Name')->text(),
                    $node->filterXPath('//Type')->text()
                );
            });
        }

        return $templates;
    }
}
