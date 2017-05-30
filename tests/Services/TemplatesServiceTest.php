<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Services;

use PHPUnit\Framework\TestCase;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;
use ExpertSenderFr\ExpertSenderApi\Model\Template;
use ExpertSenderFr\ExpertSenderApi\Services\ApiService;
use ExpertSenderFr\ExpertSenderApi\Services\Templates;
use ExpertSenderFr\ExpertSenderApi\Test\FakeApiRequest;
use ExpertSenderFr\ExpertSenderApi\Tests\TestClient;

class TemplatesServiceTest extends TestCase
{
    /**
     * @test
     */
    public function canBeInitialized()
    {
        $client = $this->createMock(ExpertSenderClient::class);

        $service = new Templates($client, 'http://example.com');

        $this->assertInstanceOf(ApiService::class, $service);
        $this->assertAttributeSame($client, 'client', $service);
        $this->assertAttributeSame('http://example.com', 'domain', $service);
    }

    /**
     * @test
     */
    public function tryGet()
    {
        $responseBody = <<<EOF
<ApiResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <Data>
    <Templates>
      <Template>
        <Id>1</Id>
        <Type>Footer</Type>
        <Name>Default EN footer</Name>
      </Template>
      <Template>
        <Id>2</Id>
        <Type>Footer</Type>
        <Name>Default PL footer</Name>
      </Template>
      <Template>
        <Id>3</Id>
        <Type>Header</Type>
        <Name>My custom header</Name>
      </Template>
      <Template>
        <Id>4</Id>
        <Type>Header</Type>
        <Name>Some other header</Name>
      </Template>
    </Templates>
  </Data>
</ApiResponse>
EOF;


        $response = new ApiResponse($responseBody, 200, []);

        $request = new FakeApiRequest();
        $request->setResponse($response);

        $client = new TestClient();
        $client->setNextRequest($request);

        $service = new Templates($client, 'http://example.com');

        /** @var Template[] $templates */
        $templates = $service->get(null, ['api_key' => 'fake']);

        $this->assertInternalType('array', $templates);
        $this->assertContainsOnly(Template::class, $templates);
        $this->assertSame(1, $templates[0]->getExternalId());
        $this->assertSame('Default EN footer', $templates[0]->getName());
        $this->assertSame('Footer', $templates[0]->getType());
        $this->assertSame(2, $templates[1]->getExternalId());
        $this->assertSame('Default PL footer', $templates[1]->getName());
        $this->assertSame('Footer', $templates[1]->getType());
        $this->assertSame(3, $templates[2]->getExternalId());
        $this->assertSame('My custom header', $templates[2]->getName());
        $this->assertSame('Header', $templates[2]->getType());
        $this->assertSame(4, $templates[3]->getExternalId());
        $this->assertSame('Some other header', $templates[3]->getName());
        $this->assertSame('Header', $templates[3]->getType());
    }
}
