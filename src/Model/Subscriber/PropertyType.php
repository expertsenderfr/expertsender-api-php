<?php

namespace ExpertSenderFr\ExpertSenderApi\Model\Subscriber;


final class PropertyType
{
    const TEXT = 'Text';
    const NUMBER = 'Number';
    const MONEY = 'Money';
    const DATE = 'Date';
    const DATETIME = 'Datetime';
    const BOOLEAN = 'Boolean';
    const URL = 'Url';
    const SINGLE_SELECT = 'SingleSelect';

    private static $xmlTypes = [
        'Text' => 'xs:string',
        'Number' => 'xs:integer',
        'Money' => 'xs:decimal',
        'Date' => 'xs:date',
        'Datetime' => 'xs:dateTime',
        'Boolean' => 'xs:boolean',
        'Url' => 'xs:string',
        'SingleSelect' => 'xs:string'
    ];

    /**
     * @var string
     */
    private $value;

    public static function getPossibleValues()
    {
        return [
            self::TEXT,
            self::NUMBER,
            self::MONEY,
            self::DATE,
            self::DATETIME,
            self::BOOLEAN,
            self::URL,
            self::SINGLE_SELECT,
        ];
    }

    public function __construct($value)
    {
        $this->guard($value);

        $this->value = $value;
    }

    private function guard($value)
    {
        if (!in_array($value, self::getPossibleValues(), true)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" is not a valid type for a property', $value)
            );
        }
    }

    public function value()
    {
        return $this->value;
    }

    public function xmlType()
    {
        return self::$xmlTypes[$this->value];
    }

    public function __toString()
    {
        return $this->value;
    }
}
