<?php

namespace ExpertSenderFr\ExpertSenderApi\Tests\Model\Subscriber;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AdditionMode;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberOptions;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\MatchingMode;
use PHPUnit\Framework\TestCase;

class AddSubscriberOptionsTest extends TestCase
{
    /**
     * @test
     */
    public function canDisallowUnsubscribed()
    {
        $options = new AddSubscriberOptions(
            new AdditionMode(),
            new MatchingMode()
        );

        $options->disallowUnsubscribed();

        $this->assertAttributeSame(false, 'allowUnsubscribed', $options);
    }

    /**
     * @test
     */
    public function canDisallowRemoved()
    {
        $options = new AddSubscriberOptions(
            new AdditionMode(),
            new MatchingMode()
        );

        $options->disallowRemoved();

        $this->assertAttributeSame(false, 'allowRemoved', $options);
    }
}
