<?php

namespace Services;

use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\Model\RemoteList;
use ExpertSenderFr\ExpertSenderApi\Services\Lists;
use ExpertSenderFr\ExpertSenderApi\Test\FakeApiRequest;
use ExpertSenderFr\ExpertSenderApi\Test\TestClient;
use PHPUnit\Framework\TestCase;

class ListsServiceTest extends TestCase
{
    public function testGet()
    {
        $responseBody = <<<EOF
<ApiResponse xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <Data>
    <Lists>
      <List>
        <Id>1</Id>
        <Name>clients</Name>
        <FriendlyName>My Clients</FriendlyName>
        <Language>en-US</Language>
        <OptInMode>DoubleOptIn</OptInMode>
      </List>
      <List>
        <Id>2</Id>
        <Name>friends</Name>
        <FriendlyName>My Friends</FriendlyName>
        <Language>ru-RU</Language>
        <OptInMode>SingleOptIn</OptInMode>
      </List>
    </Lists>
  </Data>
</ApiResponse>
EOF;

        $response = new ApiResponse($responseBody, 200, []);

        $request = new FakeApiRequest();
        $request->setResponse($response);

        $client = new TestClient();
        $client->setNextRequest($request);

        $service = new Lists($client, 'http://example.com');

        /** @var RemoteList[] $lists */
        $lists = $service->get('bat', ['api_key' => 'fake']);

        $this->assertInternalType('array', $lists);
        $this->assertContainsOnly(RemoteList::class, $lists);
        $this->assertSame(1, $lists[0]->getExternalId());
        $this->assertSame('clients', $lists[0]->getName());
        $this->assertSame(2, $lists[1]->getExternalId());
        $this->assertSame('friends', $lists[1]->getName());
    }
}

