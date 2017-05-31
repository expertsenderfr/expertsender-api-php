<?php

namespace ExpertSenderFr\ExpertSenderApi\Test;

use ExpertSenderFr\ExpertSenderApi\ApiRequest;
use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;
use PHPUnit\Framework\Assert;

class AssertRequestClient extends ExpertSenderClient
{
    private $expectedUrl;

    private $expectedMethod;

    private $expectedContent;

    protected function createRequest($url, array $parameters, $method, $content)
    {
        $request = parent::createRequest($url, $parameters, $method, $content);

        $this->assertRequest($request);

        return new NullRequest();
    }

    public function expectedRequest($url, $method, $content)
    {
        $this->expectedUrl = $url;
        $this->expectedMethod = $method;
        $this->expectedContent = $content;
    }

    private function assertRequest(ApiRequest $request)
    {
        $reflClass = new \ReflectionClass(get_class($request));

        $properties = [
            'url' => $this->expectedUrl,
            'method' => $this->expectedMethod,
            'content' => $this->expectedContent,
        ];

        foreach ($properties as $property => $expectedValue) {
            $reflProperty = $reflClass->getProperty($property);
            $reflProperty->setAccessible(true);

            Assert::assertSame($expectedValue, $reflProperty->getValue($request));
        }
    }
}
