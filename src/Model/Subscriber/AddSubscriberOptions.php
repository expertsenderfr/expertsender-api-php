<?php

namespace ExpertSenderFr\ExpertSenderApi\Model\Subscriber;

class AddSubscriberOptions
{
    /**
     * @var AdditionMode
     */
    private $mode;

    /**
     * @var MatchingMode
     */
    private $matchingMode;

    /**
     * @var bool
     */
    private $force;

    /**
     * @var bool
     */
    private $allowUnsubscribed = true;

    /**
     * @var bool
     */
    private $allowRemoved = true;

    /**
     * @param AdditionMode $mode
     * @param bool $force
     * @param MatchingMode $matchingMode
     */
    public function __construct(AdditionMode $mode, MatchingMode $matchingMode, $force = false)
    {
        $this->mode = $mode;
        $this->force = $force;
        $this->matchingMode = $matchingMode;
    }

    public function disallowUnsubscribed()
    {
        $this->allowUnsubscribed = false;
    }

    public function disallowRemoved()
    {
        $this->allowRemoved = false;
    }

    public function areUnsubscribedAllowed()
    {
        return $this->allowUnsubscribed;
    }

    public function areRemovedAllowed()
    {
        return $this->allowRemoved;
    }

    public function isForcedAddition()
    {
        return $this->force;
    }

    public function getAdditionMode()
    {
        return $this->mode;
    }

    public function getMatchingMode()
    {
        return $this->matchingMode;
    }
}