<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Model\Subscriber;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\PropertyType;
use PHPUnit\Framework\TestCase;

class PropertyTypeTest extends TestCase
{
    /**
     * @test
     */
    public function canGetAnArrayWithThePossibleValues()
    {
        $possibleValues = PropertyType::getPossibleValues();

        $this->assertContains('Text', $possibleValues);
        $this->assertContains('Number', $possibleValues);
        $this->assertContains('Money', $possibleValues);
        $this->assertContains('Date', $possibleValues);
        $this->assertContains('Datetime', $possibleValues);
        $this->assertContains('Boolean', $possibleValues);
        $this->assertContains('Url', $possibleValues);
        $this->assertContains('SingleSelect', $possibleValues);
        $this->assertCount(8, $possibleValues);
    }

    /**
     * @test
     */
    public function throwsExceptionForInvalidValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        new PropertyType('InvalidType');
    }

    /**
     * @test
     */
    public function canGetTheValue()
    {
        $type = new PropertyType(PropertyType::TEXT);

        $this->assertSame(PropertyType::TEXT, $type->value());
    }

    /**
     * @test
     */
    public function canBeCastToString()
    {
        $type = new PropertyType(PropertyType::TEXT);

        $this->assertSame(PropertyType::TEXT, (string) $type);
    }

    /**
     * @test
     */
    public function canGetTheXmlType()
    {
        $data = [
            PropertyType::TEXT => 'xs:string',
            PropertyType::NUMBER => 'xs:integer',
            PropertyType::MONEY => 'xs:decimal',
            PropertyType::DATE => 'xs:date',
            PropertyType::DATETIME => 'xs:dateTime',
            PropertyType::BOOLEAN => 'xs:boolean',
            PropertyType::URL => 'xs:string',
            PropertyType::SINGLE_SELECT => 'xs:string'
        ];

        foreach ($data as $type => $expectedXmlType) {
            $type = new PropertyType($type);

            $this->assertSame($expectedXmlType, $type->xmlType());
        }
    }
}
