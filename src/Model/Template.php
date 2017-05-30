<?php

namespace ExpertSenderFr\ExpertSenderApi\Model;

class Template
{
    private $externalId;

    private $name;

    private $type;

    public function __construct($id, $name, $type)
    {
        $this->externalId = $id;
        $this->name = $name;
        $this->setType($type);
    }

    public function getExternalId()
    {
        return $this->externalId;
    }

    public function getName()
    {
        return $this->name;
    }

    private function setType($type)
    {
        $allowedTypes = [
            TemplateType::HEADER,
            TemplateType::FOOTER,
        ];

        if (!in_array($type, $allowedTypes, true)) {
            throw new \InvalidArgumentException(
                sprintf('Unkown type "%s". Valid types are %s', $type, implode(', ', $allowedTypes))
            );
        }

        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
