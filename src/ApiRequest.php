<?php

namespace ExpertSenderFr\ExpertSenderApi;

class ApiRequest
{
    const REQUEST_GET = 'GET';
    const REQUEST_POST = 'POST';
    const REQUEST_PUT = 'PUT';
    const REQUEST_PATCH = 'PATCH';
    const REQUEST_DELETE = 'DELETE';
    /**
     * @var string
     */
    private $url;
    /**
     * @var string[]
     */
    private $parameters;
    /**
     * Possible values are self::REQUEST_GET, self::REQUEST_POST, self::REQUEST_PATCH and self::REQUEST_DELETE
     *
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $content;

    /**
     * ApiRequest constructor.
     *
     * @param string $url
     * @param array  $parameters
     * @param string $method
     */
    public function __construct($url, array $parameters, $method = self::REQUEST_GET, $content = '')
    {
        $this->url = $url;
        $this->parameters = $parameters;

        $this->checkIfMethodIsValid($method);
        $this->method = $method;
        $this->content = $content;
    }

    private function checkIfMethodIsValid($method)
    {
        $allowedMethods = [
            static::REQUEST_GET,
            static::REQUEST_POST,
            static::REQUEST_PUT,
            static::REQUEST_PATCH,
            static::REQUEST_DELETE
        ];

        if (!in_array($method, $allowedMethods, false)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Unkown method "%s". Allowed methods are %s and %s',
                    $method,
                    implode(', ', array_slice($allowedMethods, 0, -1)),
                    array_slice($allowedMethods, -1)[0]
                )
            );
        }

        return true;
    }

    public function send()
    {
        $handler = curl_init($this->url . '?' . http_build_query($this->parameters));
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $this->method);

        curl_setopt($handler, CURLOPT_POSTFIELDS, $this->content);

        if (preg_match('/^https/', $this->url)) {
            curl_setopt($handler, CURLOPT_PORT, 443);
            curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handler, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($handler, CURLOPT_HTTPHEADER, [
            'Content-Type: text/xml;charset=UTF-8',
            'Content-Length: '.strlen($this->content)
        ]);

        curl_setopt($handler, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handler, CURLOPT_HEADER, false);
        // Headers will be stored in the headers variable
        $headers = [];
        curl_setopt($handler, CURLOPT_HEADERFUNCTION, function ($handler, $header) use (&$headers) {
            $matches = [];

            if (preg_match('/^([^:]+)\s*:\s*([^\x0D\x0A]*)\x0D?\x0A?$/', $header, $matches)) {
                $headers[$matches[1]][] = $matches[2];
            }

            return strlen($header);
        });
//        curl_setopt($handler, CURLOPT_POST, false);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

        $body = curl_exec($handler);

        if (curl_errno($handler)) {
            $error = curl_error($handler);
            curl_close($handler);
            throw new \RuntimeException($error);
        }

        $response = new ApiResponse($body, curl_getinfo($handler, CURLINFO_HTTP_CODE), $headers);

        if ($response->getStatusCode() >= 400) {
            curl_close($handler);
            throw new \RuntimeException($response->getCrawler()->filterXPath('//Message')->text());
        }

        curl_close($handler);

        return $response;
    }
}
