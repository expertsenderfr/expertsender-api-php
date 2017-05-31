<?php

namespace ExpertSenderFr\ExpertSenderApi\Model\Subscriber;

use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberOptions;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\Subscriber;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\SubscriberProperty;

class AddSubscriberPayload
{
    /**
     * @var Subscriber
     */
    private $subscribers;

    /**
     * @var AddSubscriberOptions
     */
    private $options;

    /**
     * @var int
     */
    private $listId;

    /**
     * @param int $listId
     * @param Subscriber $subscriber
     * @param AddSubscriberOptions $options
     */
    public function __construct($listId, Subscriber $subscriber, AddSubscriberOptions $options)
    {
        $this->subscriber = $subscriber;
        $this->options = $options;
        $this->listId = $listId;
    }

    private function convertOptionsToArray()
    {
        return [
            'Mode' => $this->options->getAdditionMode()->value(),
            'MatchingMode' => $this->options->getMatchingMode()->value(),
            'Force' => $this->getBooleanAsString($this->options->isForcedAddition()),
            'AllowUnsubscribed' => $this->getBooleanAsString($this->options->areUnsubscribedAllowed()),
            'AllowRemoved' => $this->getBooleanAsString($this->options->areRemovedAllowed()),
        ];
    }

    public function toArray()
    {
        return [
            'Data' => [
                '@xsi:type' => 'Subscriber',
                '#' => array_merge(
                    ['ListId' => $this->listId],
                    $this->convertOptionsToArray(),
                    $this->convertSubscriberToArray()
                )
            ]
        ];
    }

    private function convertSubscriberToArray()
    {
        $result = [
            'Email' => $this->subscriber->getEmail(),
        ];

        if ($this->subscriber->getPhone() !== null) {
            $result['Phone'] = $this->subscriber->getPhone();
        }

        if ($this->subscriber->getCustomSubscriberId() !== null) {
            $result['CustomSubscriberId'] = $this->subscriber->getCustomSubscriberId();
        }

        if ($this->subscriber->getFirstName() !== null) {
            $result['Firstname'] = $this->subscriber->getFirstName();
        }

        if ($this->subscriber->getLastName() !== null) {
            $result['Lastname'] = $this->subscriber->getLastName();
        }

        if ($this->subscriber->getTrackingCode() !== null) {
            $result['TrackingCode'] = $this->subscriber->getTrackingCode();
        }

        if ($this->subscriber->getName() !== null) {
            $result['Name'] = $this->subscriber->getName();
        }

        if ($this->subscriber->getVendor() !== null) {
            $result['Vendor'] = $this->subscriber->getVendor();
        }

        if ($this->subscriber->getIp() !== null) {
            $result['Ip'] = $this->subscriber->getIp();
        }

        if (count($this->subscriber->getProperties()) === 0) {
            return $result;
        }

        $result['Properties'] = [];
        /** @var SubscriberProperty $property */
        foreach ($this->subscriber->getProperties() as $property) {
            if (!isset($result['Properties']['Property'])) {
                $result['Properties']['Property'] = [];
            }

            $result['Properties']['Property'][] = [
                'Id' => $property->id(),
                'Value' => [
                    '@xsi:type' => $property->type()->xmlType(),
                    '#' => $property->value(),
                ]
            ];
        }

        return $result;
    }

    private function getBooleanAsString($value)
    {
        return $value ? 'true' : 'false';
    }
}
