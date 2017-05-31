<?php

namespace ExpertSenderFr\ExpertSenderApi\Model\Subscriber;

final class MatchingMode
{
    const EMAIL = 'Email';
    const CUSTOM_SUBSCRIBER_ID = 'CustomSubscriberId';
    const ID = 'Id';
    const PHONE = 'Phone';

    /**
     * @var string
     */
    private $value;

    public static function getPossibleValues()
    {
        return [
            self::EMAIL,
            self::CUSTOM_SUBSCRIBER_ID,
            self::ID,
            self::PHONE,
        ];
    }

    public function __construct($value = self::EMAIL)
    {
        $this->guard($value);

        $this->value = $value;
    }

    private function guard($value)
    {
        if (!in_array($value, self::getPossibleValues(), true)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" is not a valid matching mode', $value)
            );
        }
    }

    public function value()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
