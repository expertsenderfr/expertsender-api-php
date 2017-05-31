<?php

namespace ExpertSenderFr\ExpertSenderApi\Model\Subscriber;

class Subscriber
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var null|string
     */
    private $phone;

    /**
     * @var string
     */
    private $customSubscriberId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $trackingCode;

    /**
     * @var string
     */
    private $vendor;

    /**
     * @var string
     */
    private $ip;


    /**
     * @param string $email
     * @param string|null $phone
     */
    public function __construct($email, $phone = null)
    {
        $this->email = $email;
        $this->phone = $phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function addProperty(SubscriberProperty $property)
    {
        $this->properties[$property->id()] = $property;
    }

    public function getProperty($id)
    {
        if (!isset($this->properties[$id])) {
            throw new \RuntimeException(
                sprintf('Unkown property "%s"', $id)
            );
        }

        return $this->properties[$id];
    }

    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return string
     */
    public function getCustomSubscriberId()
    {
        return $this->customSubscriberId;
    }

    /**
     * @param string $customSubscriberId
     */
    public function setCustomSubscriberId($customSubscriberId)
    {
        $this->customSubscriberId = $customSubscriberId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getTrackingCode()
    {
        return $this->trackingCode;
    }

    /**
     * @param string $trackingCode
     */
    public function setTrackingCode($trackingCode)
    {
        $this->trackingCode = $trackingCode;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }
}
