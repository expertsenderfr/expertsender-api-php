<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Model;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\PropertyType;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\Subscriber;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\SubscriberProperty;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function throwsExceptionIfPropertyDoesNotExist()
    {
        $subscriber = new Subscriber('john@example.com');

        $this->expectException(\RuntimeException::class);
        $subscriber->getProperty(3);

    }

    /**
     * @test
     */
    public function propertiesCanBeAdded()
    {
        $property = new SubscriberProperty(1, new PropertyType(PropertyType::TEXT), 'Test');

        $subscriber = new Subscriber('john@example.com');

        $subscriber->addProperty($property);

        $this->assertSame($property, $subscriber->getProperty(1));
    }

    /**
     * @test
     */
    public function canSetProperties()
    {
        $subscriber = new Subscriber('john@example.com');
        $subscriber->setCustomSubscriberId(56);
        $subscriber->setName('John Smith');
        $subscriber->setFirstName('John');
        $subscriber->setLastName('Smith');
        $subscriber->setTrackingCode(12345);
        $subscriber->setVendor('test');
        $subscriber->setIp('127.0.0.1');

        $this->assertAttributeSame(56, 'customSubscriberId', $subscriber);
        $this->assertAttributeSame('John Smith', 'name', $subscriber);
        $this->assertAttributeSame('John', 'firstName', $subscriber);
        $this->assertAttributeSame('Smith', 'lastName', $subscriber);
        $this->assertAttributeSame(12345, 'trackingCode', $subscriber);
        $this->assertAttributeSame('test', 'vendor', $subscriber);
        $this->assertAttributeSame('127.0.0.1', 'ip', $subscriber);
    }
}
