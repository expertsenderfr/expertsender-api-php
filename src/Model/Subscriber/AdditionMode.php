<?php

namespace ExpertSenderFr\ExpertSenderApi\Model\Subscriber;

final class AdditionMode
{
    const ADD_UPDATE = 'AddAndUpdate';
    const ADD_REPLACE = 'AddAndReplace';
    const ADD_IGNORE = 'AddAndIgnore';
    const IGNORE_UPDATE = 'IgnoreAndUpdate';
    const IGNORE_REPLACE = 'IgnoreAndReplace';

    private $value;

    public static function getPossibleValues()
    {
        return [
            self::ADD_UPDATE,
            self::ADD_REPLACE,
            self::ADD_IGNORE,
            self::IGNORE_UPDATE,
            self::IGNORE_REPLACE,
        ];
    }

    private function guard($value)
    {
        if (!in_array($value, self::getPossibleValues(), true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid mode "%s"', $value)
            );
        }
    }

    public function __construct($mode = self::ADD_UPDATE)
    {
        $this->guard($mode);

        $this->value = $mode;
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