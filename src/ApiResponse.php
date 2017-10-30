<?php

namespace ExpertSenderFr\ExpertSenderApi;

use DOMDocument;
use ExpertSenderFr\ExpertSenderApi\Exception\XmlParserError;
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
        if (!$this->crawler || !($this->crawler instanceof Crawler)) {
            $xml = $this->loadXml();

            $this->crawler = new Crawler();
            $this->crawler->addDocument($xml);
        }

        return $this->crawler;
    }

    public function __get($name)
    {
        if (!$this->crawler) {
            $this->crawler = $this->loadXml();
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

    /**
     * @return DOMDocument
     * @throws \Exception
     */
    private function loadXml()
    {
        try {
            $xml = new DOMDocument();

            if (!@$xml->loadXML($this->body)) {
                throw XmlParserError::fromString($this->body);
            }
        } catch (\Exception $e) {
            if ($e instanceof XmlParserError) {
                throw $e;
            }

            $exception = new \Exception(
                implode(';', [
                    $e->getMessage(),
                    $this->body
                ])
            );

            throw $exception;
        }
        return $xml;
    }
}
