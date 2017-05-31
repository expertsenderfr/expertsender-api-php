Subscribers Service
===================

This service is used for managing subscriptions on ExpertSender using their API.

Supported operations at this time are:
 - [Add/Update subscriptions](#adding-a-subscription)

Adding a subscription
---------------------
To send a subscription to a list, you will need to create in instance of the class `\ExpertSenderFr\ExpertSenderApi\Model\Subscriber\Subscriber`.

After that you should pass it as parameter to the subscribers service. You can also choose to pass an instance of `\ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberOptions`
where you can define some of the options supported by the API.

```php
<?php

$client = new \ExpertSenderFr\ExpertSenderApi\ExpertSenderClient('<YOUR_API_KEY>', '<YOUR_DOMAIN>');

$listId = 34;

$subscriber = new \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\Subscriber('john.smith@example.com');
$subscriber->setFirstName('John');
$subscriber->setLastName('Smith');
$subscriber->setIp('127.0.0.1');
$subscriber->addProperty(
    new \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\SubscriberProperty(
        1,
        new \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\PropertyType(
            \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\PropertyType::DATE
        ),
        '1978-08-30'
    )
);

$options = new \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AddSubscriberOptions(
    new \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AdditionMode(
        \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\AdditionMode::ADD_UPDATE
    ),
    new \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\MatchingMode(
        \ExpertSenderFr\ExpertSenderApi\Model\Subscriber\MatchingMode::EMAIL
    )
);

$options->disallowUnsubscribed();

$client->subscribers()->addSubscriber($listId, $subscriber, ['options' => $options]);
```

### Available addition modes
 - ADD_UPDATE: Add new subscribers and update custom fields of subscribers existing on the list.
 - ADD_REPLACE: Add new subscribers and replace custom fields of subscribers existing on the list (NOTE: all previous values of custom fields will be erased).
 - ADD_IGNORE: Add new subscribers and do not update or replace custom fields of subscribers existing on the list.
 - IGNORE_UPDATE: Do not add new subscribers, only update custom fields of subscribers existing on the list.
 - IGNORE_REPLACE: Do not add new subscribers, only replace custom fields of subscribers existing on the list (NOTE: all previous values of custom fields will be erased).

### Available MatchingModes
 - EMAIL: The Email will be used as primary identifier of the subscriber
 - ID: The Id will be used as primary identifier of the subscriber
 - CUSTOM_SUBSCRIBER_ID: The CustomSubscriberId will be used as primary identifier of the subscriber
 - PHONE: The Phone will be used as primary identifier of the subscriber

**INFO** Phone is only available if the unit has access to SMS channel.

### Available Property types
 - TEXT
 - NUMBER
 - MONEY
 - DATE
 - DATETIME
 - BOOLEAN
 - URL
 - SINGLE_SELECT: Will accept only one of predefined values for this particular property.
