<?php

namespace ExpertSenderFr\ExpertSenderApi\Model\Subscriber;

class SubscriberProperty
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var PropertyType
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    /**
     * SubscriberProperty constructor.
     * @param int $id
     * @param PropertyType $type
     * @param mixed $value
     */
    public function __construct($id, PropertyType $type, $value)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return PropertyType
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}
