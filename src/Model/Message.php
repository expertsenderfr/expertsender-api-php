<?php

namespace TDF\ExpertSenderApi\Model;

use DateTime;

class Message
{
    const THROTTLING_METHOD_NONE = 'None';
    const THROTTLING_METHOD_AUTO = 'Auto';
    const THROTTLING_METHOD_MANUAL = 'Manual';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $type;

    /**
     * @var DateTime
     */
    private $sentDate;

    /**
     * @var string[]
     */
    private $tags;

    /**
     * @var int
     */
    private $throttling;

    /**
     * @var string
     */
    private $throttlingMethod;

    /**
     * @var string[]
     */
    private $googleAnalyticsTags;

    private $yandexListId;

    private $channels;

    private $lists;

    private $segments;

    private $status;

    /**
     * Message constructor.
     *
     * @param int    $id
     * @param string $fromName
     * @param string $fromEmail
     * @param string $subject
     * @param string $type
     */
    public function __construct($id, $fromName, $fromEmail, $subject, $type)
    {
        $this->id = $id;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->subject = $subject;
        $this->type = $type;
    }

    public function addTags($tags)
    {
        $this->tags[] = explode(',', $tags);
    }

    public function addGoogleAnalyticsTag($name, $value)
    {
        $this->googleAnalyticsTags[$name] = $value;
    }

    public function yandexListId($id, $name)
    {
        $this->yandexListId[$id] = $name;
    }

    public function addSegment($id, $name)
    {
        $this->segments[$id] = $name;
    }

    public function addList($id, $name)
    {
        $this->lists[$id] = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getSentDate()
    {
        return $this->sentDate;
    }

    /**
     * @param DateTime $sentDate
     */
    public function setSentDate(DateTime $sentDate)
    {
        $this->sentDate = $sentDate;
    }

    /**
     * @return \string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return int
     */
    public function getThrottling()
    {
        return $this->throttling;
    }

    public function setThrottling($method, $throttling = null)
    {
        $this->throttlingMethod = $method;
        $this->throttling = $throttling;
    }

    /**
     * @return string
     */
    public function getThrottlingMethod()
    {
        return $this->throttlingMethod;
    }

    /**
     * @return \string[]
     */
    public function getGoogleAnalyticsTags()
    {
        return $this->googleAnalyticsTags;
    }

    /**
     * @return mixed
     */
    public function getYandexListId()
    {
        return $this->yandexListId;
    }

    /**
     * @return mixed
     */
    public function getChannels()
    {
        return $this->channels;
    }

    public function setChannels($channels)
    {
        $this->channels = explode(',', $channels);
    }

    /**
     * @return mixed
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * @return mixed
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
