<?php

namespace TDF\ExpertSenderApi;

use PHPUnit\Framework\TestCase;
use stdClass;

class SerializerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createsSerializerSupportsXmlEncoder()
    {
        $serializer = SerializerFactory::createXmlSerializer();

        $this->assertTrue($serializer->supportsEncoding('xml'));
    }

    /**
     * @test
     */
    public function canNormalizeObjects()
    {
        $serializer = SerializerFactory::createXmlSerializer();

        $this->assertTrue($serializer->supportsNormalization(new stdClass(), 'xml'));
    }
}
