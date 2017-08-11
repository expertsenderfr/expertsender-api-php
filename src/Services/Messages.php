<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use DateTime;
use Symfony\Component\DomCrawler\Crawler;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;
use ExpertSenderFr\ExpertSenderApi\Model\Message;
use ExpertSenderFr\ExpertSenderApi\Model\NewsletterCreationPayload;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Messages extends ApiService
{
    const SERVICE_URL = 'Api/Messages';
    const NEWSLETTER_SERVICE_URL = 'Api/Newsletters';

    /**
     * Creates a new message of type Newsletter
     *
     * @param NewsletterCreationPayload $message
     * @param array                     $opts
     *
     * @return int
     * @throws \RuntimeException
     */
    public function createNewsletter(NewsletterCreationPayload $message, array $opts = [])
    {
        $response = $this->doCreate(
            ['Data' => $message->toArray()],
            array_merge(['endpoint' => static::NEWSLETTER_SERVICE_URL], $opts)
        );

        if ($response->getStatusCode() !== 201) {
            throw new \RuntimeException($response->body);
        }

        $crawler = $response->getCrawler();

        try {
            return (int)$crawler->filterXPath('//Data')->text();
        } catch (\InvalidArgumentException $e) {
            return -1;
        }
    }

    /**
     * Gets the information from the router of the message identified by $messageId
     *
     * @param int   $messageId
     * @param array $opts
     *
     * @return Message
     */
    public function get($messageId, array $opts = [])
    {
        $response = $this->doGet($messageId, [], $opts);

        return $this->parseGetResponse($response);
    }

    public function getAll(array $parameters, array $opts = [])
    {
        $response = $this->doAll($parameters, $opts);
        $crawler = $response->getCrawler();

        $result = [];
        $crawler->filterXPath('//Messages/Message')->each(function (Crawler $node, $i) use (&$result) {
            try {
                $item = [
                    'externalId' => (int)$node->filterXPath('//Id')->text(),
                    'type' => $node->filterXPath('//Type')->text()
                ];

                if ($item['type'] === 'Newsletter') {
                    $item['sentDate'] = $node->filterXPath('//SentDate')->text();
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['html' => $node->html()]);
                return;
            }

            $result[] = $item;
        });

        return $result;
    }

    private function parseGetResponse(ApiResponse $response)
    {
        $crawler = $response->getCrawler();

        $message = new Message(
            (int)$crawler->filterXPath('//ApiResponse/Data/Id')->text(),
            $crawler->filterXPath('//FromName')->text(),
            $crawler->filterXPath('//FromEmail')->text(),
            $crawler->filterXPath('//Subject')->text(),
            $crawler->filterXPath('//Type')->text()
        );

        if ($crawler->filterXPath('//SentDate')->count() !== 0) {
            $message->setSentDate(
                new DateTime($crawler->filterXPath('//SentDate')->text())
            );
        }

        $throttling = null;
        if ($crawler->filterXPath('//Throttling')->count() > 0) {
            $throttling = (int)$crawler->filterXPath('//Throttling')->text();
        }
        $message->setThrottling($crawler->filterXPath('//ThrottlingMethod')->text(), $throttling);

        if ($crawler->filterXPath('//Tags')->count() > 0) {
            $message->addTags($crawler->filterXPath('//Tags')->text());
        }

        $crawler->filterXPath('//Lists/List')->each(function (Crawler $node, $i) use ($message) {
            $message->addList(
                (int)$node->filterXPath('//Id')->text(),
                $node->filterXPath('//Name')->text()
            );
        });

        $crawler->filterXPath('//GoogleAnalyticsTags/GoogleAnalyticsTag ')->each(
            function (Crawler $node, $i) use ($message) {
                $message->addGoogleAnalyticsTag(
                    $node->filterXPath('//Name')->text(),
                    $node->filterXPath('//Value')->text()
                );
            }
        );

        if ($crawler->filterXPath('//YandexListId')->count() > 0) {
            $message->yandexListId(
                $crawler->filterXPath('//YandexListId/Identifier')->text(),
                $crawler->filterXPath('//YandexListId/Comment')->text()
            );
        }

        if ($crawler->filterXPath('//Segments')->count() > 0) {
            $crawler->filterXPath('//Segments/Segment')->each(function (Crawler $node, $i) use ($message) {
                $message->addSegment(
                    (int)$node->filterXPath('//Id')->text(),
                    $node->filterXPath('//Name')->text()
                );
            });
        }

        $message->setStatus(
            $crawler->filterXPath('//Status')->text()
        );

        return $message;
    }

    /**
     * Pause the sending of the message identified by $messageId if its sending is in progress
     *
     * @param int   $messageId
     * @param array $opts
     *
     * @return int
     * @throws \RuntimeException
     */
    public function pause($messageId, array $opts = [])
    {
        $response = $this->doUpdate(
            $messageId,
            ['Action' => 'PauseMessage'],
            array_merge(['endpoint' => static::NEWSLETTER_SERVICE_URL], $opts)
        );

        if ($response->getStatusCode() >= 400) {
            throw new \RuntimeException($response->body);
        }

        return 0;
    }

    /**
     * Resume the sending of the message identified by $messageId if its sending that has been paused
     *
     * @param int   $messageId
     * @param array $opts
     *
     * @return int
     * @throws \RuntimeException
     */
    public function resume($messageId, array $opts = [])
    {
        $response = $this->doUpdate(
            $messageId,
            ['Action' => 'ResumeMessage'],
            array_merge(['endpoint' => static::NEWSLETTER_SERVICE_URL], $opts)
        );

        if ($response->getStatusCode() >= 400) {
            throw new \RuntimeException($response->body);
        }

        return 0;
    }

    /**
     * Deletes the message identified by $messageId
     *
     * @param string $messageId The id of the message to delete
     * @param array  $opts      Options to use by the client only for this operation
     *
     * @return bool Returns true if the HTTP status code of the response ro the request is 204
     */
    public function delete($messageId, array $opts = [])
    {
        $response = $this->doDelete($messageId, [], $opts);

        return $response->getStatusCode() === 204;
    }

    protected function configureAllParameters(OptionsResolver $resolver, array $opts = [])
    {
        $resolver->setDefined([
            'startDate',
            'endDate',
            'tag',
            'type'
        ]);

        $resolver->setAllowedValues('type', [
            'Newsletter',
            'Autoresponder',
            'Trigger',
            'Transactional',
            'Confirmation',
            'Recurring',
            'Test',
            'FreeEmailPreview',
            'PaidEmailPreview',
            'WorkflowMessage'
        ]);
    }

    protected function getServiceUrl()
    {
        return static::SERVICE_URL;
    }
}
