<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Model\Subscriber;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\PropertyType;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\SubscriberProperty;
use PHPUnit\Framework\TestCase;

class SubscriberPropertyTest extends TestCase
{
    protected $property;

    public function setUp()
    {
        $this->property = new SubscriberProperty(1, new PropertyType(PropertyType::DATE), '2016-08-30');
    }

    /**
     * @test
     */
    public function canCreateInstance()
    {
        $this->assertAttributeSame(1, 'id', $this->property);
        $this->assertAttributeInstanceOf(PropertyType::class, 'type', $this->property);
        $this->assertAttributeSame('2016-08-30', 'value', $this->property);
    }

    public function canGetTheId()
    {
        $this->assertSame(1, $this->property->id());
    }

    public function canGetTheType()
    {
        $this->assertInstanceOf(PropertyType::class, $this->property->type());
    }

    public function canGetTheValue()
    {
        $this->assertInstanceOf('2016-08-30', $this->property->value());
    }
}
