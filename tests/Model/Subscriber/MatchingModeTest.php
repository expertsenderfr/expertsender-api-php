<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Model\Subscriber;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\MatchingMode;
use ExpertSenderFr\ExpertSenderApi\SerializerFactory;
use PHPUnit\Framework\TestCase;

class MatchingModeTest extends TestCase
{
    /**
     * @test
     */
    public function canGetAnArrayWithThePossibleValues()
    {
        $possibleValues = MatchingMode::getPossibleValues();

        $this->assertContains('Email', $possibleValues);
        $this->assertContains('CustomSubscriberId', $possibleValues);
        $this->assertContains('Id', $possibleValues);
        $this->assertContains('Phone', $possibleValues);
        $this->assertCount(4, $possibleValues);
    }

    /**
     * @test
     */
    public function throwsExceptionForInvalidValues()
    {
        $this->expectException(\InvalidArgumentException::class);

        new MatchingMode('InvalidMode');
    }

    /**
     * @test
     */
    public function canGetTheValue()
    {
        $mode = new MatchingMode(MatchingMode::EMAIL);

        $this->assertSame(MatchingMode::EMAIL, $mode->value());
    }

    /**
     * @test
     */
    public function canBeCastToString()
    {
        $mode = new MatchingMode(MatchingMode::EMAIL);

        $this->assertSame(MatchingMode::EMAIL, (string) $mode);
    }
}
