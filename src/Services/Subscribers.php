<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AdditionMode;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberOptions;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberPayload;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\MatchingMode;
use ExpertSenderFr\ExpertSenderApi\Model\Subscriber\Subscriber;

final class Subscribers extends ApiService
{
    const SERVICE_URL = 'Api/Subscribers';

    public function __construct(ExpertSenderClient $client, $domain)
    {
        parent::__construct($client, $domain);
    }

    public function addSubscriber($listId, Subscriber $subscriber, array $opts = [])
    {
        if (!isset($opts['options'])) {
            $opts['options'] = new AddSubscriberOptions(
                new AdditionMode(),
                new MatchingMode()
            );
        }

        if (!$opts['options'] instanceof AddSubscriberOptions) {
            throw new \InvalidArgumentException(
                sprintf('The options for the addition must be an instance of AddSubscriberOptions')
            );
        }

        $payload = new AddSubscriberPayload($listId, $subscriber, $opts['options']);
        unset($opts['options']);

        return $this->doCreate($payload->toArray(), $opts);
    }
}
