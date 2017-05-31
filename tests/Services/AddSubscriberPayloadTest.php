<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Services;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AdditionMode;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberOptions;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberPayload;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\MatchingMode;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\PropertyType;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\Subscriber;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\SubscriberProperty;
use PHPUnit\Framework\TestCase;

class AddSubscriberPayloadTest extends TestCase
{
    /**
     * @test
     */
    public function canConvertToArray()
    {
        $subscriber = new Subscriber('john@example.com');
        $subscriber->setFirstName('John');
        $subscriber->setLastName('Smith');
        $subscriber->addProperty(
            new SubscriberProperty(1, new PropertyType(PropertyType::DATE), '2016-08-30')
        );$subscriber->addProperty(
            new SubscriberProperty(2, new PropertyType(PropertyType::TEXT), 'Test')
        );

        $options = new AddSubscriberOptions(
            new AdditionMode(),
            new MatchingMode()
        );

        $payload = new AddSubscriberPayload(1, $subscriber, $options);

        $expectedArray = [
            'Data' => [
                '@xsi:type' => 'Subscriber',
                '#' => [
                    'ListId' => 1,
                    'Mode' => 'AddAndUpdate',
                    'MatchingMode' => 'Email',
                    'Force' => 'false',
                    'AllowUnsubscribed' => 'true',
                    'AllowRemoved' => 'true',
                    'Email' => 'john@example.com',
                    'Firstname' => 'John',
                    'Lastname' => 'Smith',
                    'Properties' => [
                        'Property' => [
                            [
                                'Id' => 1,
                                'Value' => [
                                    '@xsi:type' => 'xs:date',
                                    '#' => '2016-08-30'
                                ]
                            ],
                            [
                                'Id' => 2,
                                'Value' => [
                                    '@xsi:type' => 'xs:string',
                                    '#' => 'Test'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertSame($expectedArray, $payload->toArray());
    }
}
