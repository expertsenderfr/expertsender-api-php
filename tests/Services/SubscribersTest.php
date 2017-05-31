<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Services;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\PropertyType;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\Subscriber;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\SubscriberProperty;
use ExpertSenderFr\ExpertSenderApi\Services\Subscribers;
use ExpertSenderFr\ExpertSenderApi\Test\AssertRequestClient;
use PHPUnit\Framework\TestCase;

class SubscribersTest extends TestCase
{
    /**
     * @var AssertRequestClient
     */
    protected $client;

    /**
     * @var Subscribers
     */
    protected $service;

    protected function createClient()
    {
        $this->client = new AssertRequestClient();
    }

    public function setUp()
    {
        $this->createClient();

        $this->service = new Subscribers(
            $this->client,
            'https://api.example.com'
        );
    }

    /**
     * @test
     */
    public function canAddASubscriber()
    {
        $expectedRequestContent = <<<EOF
<?xml version="1.0"?>
<ApiRequest xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema"><ApiKey>1234</ApiKey><Data xsi:type="Subscriber"><ListId>1</ListId><Mode>AddAndUpdate</Mode><MatchingMode>Email</MatchingMode><Force>false</Force><AllowUnsubscribed>true</AllowUnsubscribed><AllowRemoved>true</AllowRemoved><Email>john.smith@example.com</Email><Properties><Property><Id>2</Id><Value xsi:type="xs:string">student</Value></Property><Property><Id>3</Id><Value xsi:type="xs:dateTime">1985-03-12</Value></Property></Properties></Data></ApiRequest>

EOF;


        $this->client->expectedRequest(
            'https://api.example.com/Api/Subscribers',
            'POST',
            $expectedRequestContent
        );

        $subscriber = new Subscriber('john.smith@example.com');
        $subscriber->addProperty(
            new SubscriberProperty(2, new PropertyType(PropertyType::TEXT), 'student')
        );
        $subscriber->addProperty(
            new SubscriberProperty(3, new PropertyType(PropertyType::DATETIME), '1985-03-12')

        );

        $this->service->addSubscriber(1, $subscriber, [
            'api_key' => '1234'
        ]);
    }

    /**
     * @test
     */
    public function throwsExceptionIfTheOptionsAreNotAnInstanceOfAddSubscriberOptions()
    {
        $subscriber = new Subscriber('john.smith@example.com');

        $this->expectException(\InvalidArgumentException::class);
        $this->service->addSubscriber(1, $subscriber, [
            'api_key' => '1234',
            'options' => 'foo'
        ]);
    }
}
