<?php

namespace ExpertSenderFr\ExpertSenderApi;

use DOMDocument;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ApiResponse
 */
class ApiResponse
{
    public $body;
    private $headers;
    private $statusCode;
    /**
     * @var DOMDocument
     */
    private $crawler;

    public function __construct($body, $code, $headers)
    {
        $this->body = $body;
        $this->statusCode = $code;
        $this->headers = $headers;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getCrawler()
    {
        if (!$this->crawler) {
            $xml = new DOMDocument();
            $xml->loadXML($this->body);

            $this->crawler = new Crawler();
            $this->crawler->addDocument($xml);
        }

        return $this->crawler;
    }

    public function __get($name)
    {
        if (!$this->crawler) {
            $this->crawler = new DOMDocument();
            $this->crawler->loadXML($this->body);
        }

        return $this->crawler->getElementsByTagName($name);
    }

    public function __toString()
    {
        if (!is_string($this->body)) {
            return (string) $this->body;
        }

        return $this->body;
    }
}
