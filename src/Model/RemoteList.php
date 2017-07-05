<?php

namespace ExpertSenderFr\ExpertSenderApi\Model;

class RemoteList
{
    private $externalId;
    private $name;
    private $type;

    public function __construct($externalId, $name, $type)
    {
        $this->checkType($type);
        $this->externalId = $externalId;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    private function checkType($type)
    {
        if ($type !== 'bat' && $type !== 'normal') {
            throw new \InvalidArgumentException('The possible types for a remote list are "bat" and "normal"');
        }
    }
}
