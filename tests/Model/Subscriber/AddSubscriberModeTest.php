<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Model\Subscriber;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AdditionMode;
use PHPUnit\Framework\TestCase;

class AddSubscriberModeTest extends TestCase
{
    /**
     * @test
     */
    public function canGetAllPossibleValues()
    {
        $possibleValues = AdditionMode::getPossibleValues();

        $this->assertContains(AdditionMode::ADD_UPDATE, $possibleValues);
        $this->assertContains(AdditionMode::ADD_REPLACE, $possibleValues);
        $this->assertContains(AdditionMode::ADD_IGNORE, $possibleValues);
        $this->assertContains(AdditionMode::IGNORE_UPDATE, $possibleValues);
        $this->assertContains(AdditionMode::IGNORE_REPLACE, $possibleValues);
        $this->assertCount(5, $possibleValues);
    }

    /**
     * @test
     */
    public function throwsExceptionForInvalidValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        new AdditionMode('InvalidMode');
    }

    /**
     * @test
     */
    public function canGetTheValue()
    {
        $mode = new AdditionMode(AdditionMode::IGNORE_REPLACE);

        $this->assertSame(AdditionMode::IGNORE_REPLACE, $mode->value());
    }

    /**
     * @test
     */
    public function canBeCastToString()
    {
        $mode = new AdditionMode(AdditionMode::IGNORE_REPLACE);

        $this->assertSame(AdditionMode::IGNORE_REPLACE, (string) $mode);
    }
}
