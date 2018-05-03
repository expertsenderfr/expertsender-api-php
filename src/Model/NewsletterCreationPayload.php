<?php

namespace ExpertSenderFr\ExpertSenderApi\Model;

use ExpertSenderFr\ExpertSenderApi\NoRecipientsException;

class NewsletterCreationPayload
{
    // Recipients
    private static $validTimezones = [
        'Hawaiian Standard Time',
        'Alaskan Standard Time',
        'Pacific Standard Time',
        'Mountain Standard Time',
        'Central America Standard Time',
        'Central Standard Time',
        'Eastern Standard Time',
        'Atlantic Standard Time',
        'Argentina Standard Time',
        'GMT Standard Time',
        'UTC',
        'W. Europe Standard Time',
        'Central Europe Standard Time',
        'Romance Standard Time',
        'Central European Standard Time',
        'GTB Standard Time',
        'FLE Standard Time Israel Standard Time',
        'Standard Time Arabian Standard Time',
        'Russian Standard Time',
        'Ekaterinburg Standard Time',
        'SE Asia Standard Time',
        'N. Central Asia Standard Time',
        'North Asia Standard Time',
        'Singapore Standard Time',
        'China Standard Time',
        'North Asia East Standard Time',
        'Tokyo Standard Time',
        'AUS Eastern Standard Time',
        'Yakutsk Standard Time',
        'Vladivostok Standard Time',
        'New Zealand Standard Time',
        'Magadan Standard Time',
    ];
    private $subscriberList = [];
    private $subscriberSegments = [];
    private $seedLists = [];
    private $suppressionLists = [];

    // Content
    private $subject;
    private $fromName;
    private $fromEmail;
    private $replyToName;
    private $replyToEmail;
    private $html;
    private $plainText;
    private $contentFromUrl;
    private $googleAnalyticsTags;
    private $header;
    private $footer;
    private $tags = [];
    private $attachments = [];

    // Delivery Settings
    private $urlIntegrations = [];
    private $deliveryDate;
    private $overrideDeliveryCap;
    private $throttling;
    private $channels = [];

    public function __construct($subject, $fromName, $fromEmail)
    {
        $this->subject = $subject;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
    }

    public function addSubscriberList($listId)
    {
        $this->subscriberList[] = $listId;
    }

    public function addSubscriberLists(array $listIds)
    {
        $this->subscriberList = array_merge($listIds, $this->subscriberList);
    }

    public function addSubscriberSegment($segmentId)
    {
        $this->subscriberSegments[] = $segmentId;
    }

    public function addSubscriberSegments(array $segmentIds)
    {
        $this->subscriberSegments = array_merge($segmentIds, $this->subscriberSegments);
    }

    public function addSeedList($listId)
    {
        $this->seedLists[] = $listId;
    }

    public function addSeedLists(array $listIds)
    {
        $this->seedLists = array_merge($listIds, $this->seedLists);
    }

    public function addSuppressionList($listId)
    {
        $this->suppressionLists[] = $listId;
    }

    public function addSuppressionLists(array $listIds)
    {
        $this->suppressionLists = array_merge($this->suppressionLists, $listIds);
    }

    public function setReplyTo($name, $email)
    {
        $this->replyToName = $name;
        $this->replyToEmail = $email;
    }

    public function setMessageContent($html, $plaintext)
    {
        $this->html = $html;
        $this->plainText = $plaintext;
    }

    public function setContentFromUrl($url, $username, $password, $authMethod = 'None', $activeMode = false)
    {
        if (!in_array($authMethod, ['None', 'ExplicitTls', 'ExplicitSsl', 'ImplicitSsl'], true)) {
            throw new \InvalidArgumentException('Invalid auth method.');
        }


        $this->contentFromUrl['Url'] = $url;
        $this->contentFromUrl['Username'] = $username;
        $this->contentFromUrl['Password'] = $password;
        $this->contentFromUrl['FtpAuth'] = $authMethod;
        $this->contentFromUrl['FtpUseActiveMode'] = (bool)$activeMode;
    }

    public function setGoogleAnalyticsTags($campaign, $source, $content)
    {
        $this->googleAnalyticsTags['Campaign'] = $campaign;
        $this->googleAnalyticsTags['Source'] = $source;
        $this->googleAnalyticsTags['Content'] = $content;
    }

    public function setTemplates($header, $footer = null)
    {
        if ($header !== null) {
            $this->header = $header;
        }

        if ($footer !== null) {
            $this->footer = $footer;
        }
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }

    public function addAttachment($filename, $content, $mimeType = 'application/octet-stream')
    {
        $this->attachments[] = [
            'FileName' => $filename,
            'MimeType' => $mimeType,
            'Content' => $content
        ];
    }

    public function addUrlIntegration($integrationId)
    {
        $this->urlIntegrations[] = $integrationId;
    }

    public function setDeliveryDate($date, $timezone = null)
    {
        if ($timezone !== null && !in_array($timezone, static::$validTimezones, true)) {
            throw new \InvalidArgumentException(sprintf('Unkown timezone "%s".', $timezone));
        }

        $this->deliveryDate['date'] = $date;

        if ($timezone !== null) {
            $this->deliveryDate['timezone'] = $timezone;
        }
    }

    public function setOverrideDeliveryCap($override)
    {
        $this->overrideDeliveryCap = $override;
    }

    public function setThrottlingMethod($method, array $opts = [])
    {
        $validThrottlingMethods = [
            'None',
            'Auto',
            'Manual',
            'TimeOptimized',
            'TimeTravel'
        ];

        if (!in_array($method, $validThrottlingMethods, true)) {
            throw new \InvalidArgumentException(
                sprintf('"%s" is not a valid throttling method.', $method)
            );
        }

        /**
         * @var string   $checkedMethod
         * @var string[] $options
         */
        $throttlingOptions = ['Manual' => ['throttling_time'], 'TimeOptimized' => ['optimization_period']];
        foreach ($throttlingOptions as $checkedMethod => $options) {
            foreach ($options as $option) {
                if ($method === $checkedMethod && !isset($opts[$option])) {
                    throw new \InvalidArgumentException(
                        sprintf('The throttling method "%s" requires the option "%s".', $method, $option)
                    );
                }
            }
        }

        if (isset($opts['optimization_period']) && !in_array($opts['optimization_period'], ['24h', '7d'], true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid value for "optimization_period", received "%s". Valid values are "24h" and "7d"',
                    $opts['optimization_period']
                )
            );
        }

        $this->throttling['method'] = $method;

        if (isset($opts['throttling_time'])) {
            $this->throttling['throttling_time'] = $opts['throttling_time'];
        }

        if (isset($opts['optimization_period'])) {
            $this->throttling['optimization_period'] = $opts['throttling_time'];
        }
    }

    public function addDeliveryChannel($ipAddress, $percentage)
    {
        $this->channels[] = [
            'Ip' => $ipAddress,
            'Percentage' => $percentage,
        ];
    }

    public function toArray()
    {
        if (count($this->subscriberList) === 0 && count($this->subscriberSegments) === 0 &&
            count($this->seedLists) === 0 && count($this->suppressionLists) === 0
        ) {
            throw new NoRecipientsException();
        }

        $result = [
            'Recipients' => [
            ],
//            'Content' => $this->content,
            'Content' => [
                'Subject' => $this->subject,
                'FromName' => $this->fromName,
                'FromEmail' => $this->fromEmail,
                'ReplyToName' => $this->replyToName,
                'ReplyToEmail' => $this->replyToEmail,
                'Html' => $this->html,
                'Plain' => $this->plainText,
            ]
        ];

        if (count($this->subscriberList) > 0) {
            $result['Recipients']['SubscriberLists']['SubscriberList'] = $this->subscriberList;
        }
        if (count($this->subscriberSegments) > 0) {
            $result['Recipients']['SubscriberSegments']['SubscriberSegment'] = $this->subscriberSegments;
        }
        if (count($this->seedLists) > 0) {
            $result['Recipients']['SeedLists']['SeedList'] = $this->seedLists;
        }
        if (count($this->suppressionLists) > 0) {
            $result['Recipients']['SuppressionLists']['SuppressionList'] = $this->suppressionLists;
        }

        if ($this->contentFromUrl !== null) {
            $result['Content']['ContentFromUrl'] = [
                'Url' => $this->contentFromUrl['Url'],
                'Username' => $this->contentFromUrl['Username'],
                'Password' => $this->contentFromUrl['Password'],
                'FtpAuth' => $this->contentFromUrl['FtpAuth'],
                'FtpUseActiveMode' => $this->contentFromUrl['FtpUseActiveMode'],
            ];
        }

        if ($this->googleAnalyticsTags !== null) {
            $result['Content']['GoogleAnalyticsTags'] = [
                'Campaign' => $this->googleAnalyticsTags['Campaign'],
                'Source' => $this->googleAnalyticsTags['Source'],
                'Content' => $this->googleAnalyticsTags['Content'],
            ];
        }

        if ($this->header !== null) {
            $result['Content']['Header'] = $this->header;
        }

        if ($this->footer !== null) {
            $result['Content']['Footer'] = $this->footer;
        }

        if (count($this->tags) > 0) {
            $result['Content']['Tags']['Tag'] = $this->tags;
        }

        if (count($this->attachments) > 0) {
            $result['Attachments']['Attachment'] = $this->attachments;
        }

        if (count($this->urlIntegrations) > 0) {
            foreach ($this->urlIntegrations as $integration) {
                $result['UtlIntegrations']['UrlIntegration'][] = ['Id' => $integration];
            }
        }

        if ($this->deliveryDate !== null) {
            $result['DeliverySettings']['DeliveryDate'] = $this->deliveryDate['date']->format('Y-m-d\\TH:i:s');

            if (isset($this->deliveryDate['timezone'])) {
                $result['DeliverySettings']['TimeZone'] = $this->deliveryDate['timezone'];
            }
        }

        if ($this->overrideDeliveryCap !== null) {
            $result['DeliverySettings']['OverrideDeliveryCap'] = $this->overrideDeliveryCap;
        }

        if ($this->throttling !== null) {
            $result['DeliverySettings']['ThrottlingMethod'] = $this->throttling['method'];

            if (isset($this->throttling['throttling_time'])) {
                $result['DeliverySettings']['ManualThrottlingTime'] = $this->throttling['throttling_time'];
            }


            if (isset($this->throttling['optimization_period'])) {
                $result['DeliverySettings']['TimeOptimizationPeriod'] = $this->throttling['optimization_period'];
            }
        }

        if (count($this->channels) > 0) {
            $result['DeliverySettings']['Channels']['Channel'] = $this->channels;
        }

        return $result;
    }
}
