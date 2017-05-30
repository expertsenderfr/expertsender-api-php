<?php

namespace TDF\ExpertSenderApi\Services;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TDF\ExpertSenderApi\ApiResponse;
use TDF\ExpertSenderApi\Model\SignalSpamReport;

/**
 * Class SignalSpamStatistics
 *
 * @author Isaac Rozas García <isaac.rozgar@gmail.com>
 */
class SignalSpamStatistics extends ApiService
{
    const SERVICE_URL = 'Api/SignalSpamStatistics';

    /**
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     * @param array          $parameters
     * @param array          $opts
     *
     * @return SignalSpamReport[]
     */
    public function get(\DateTime $startDate = null, \DateTime $endDate = null, array $parameters = [], array $opts = [])
    {
        if ($startDate) {
            $parameters['startDate'] = $startDate->format('Y-m-d');
        }
        if ($endDate) {
            $parameters['endDate'] = $endDate->format('Y-m-d');
        }

        return $this->createResponseObject(
            $this->doAll($parameters, $opts)
        );
    }

    private function createResponseObject(ApiResponse $responseXml)
    {
        $response = [];
        foreach ($responseXml->SignalSpamStatistic as $statistic) {
            $model = new SignalSpamReport();

            if ($statistic->getElementsByTagName('IsSummaryRow')->length > 0) {
                $model->isSummaryRow = true;
            }

            $model->delivered = (int)$statistic->getElementsByTagName('Delivered')->item(0)->nodeValue;
            $model->complaints = (int)$statistic->getElementsByTagName('Complaints')->item(0)->nodeValue;
            $model->complaintRate = (int)((float)$statistic->getElementsByTagName('ComplaintRate')->item(0)->nodeValue * 100);
            $model->spamTraps = (int)$statistic->getElementsByTagName('Spamtraps')->item(0)->nodeValue;

            $providerNodes = $statistic->getElementsByTagName('Provider');
            if ($providerNodes->length > 0) {
                $model->provider = $providerNodes->item(0)->nodeValue;
            }

            $response[] = $model;
        }

        return $response;
    }

    protected function getServiceUrl()
    {
        return self::SERVICE_URL;
    }

    protected function configureAllParameters(OptionsResolver $resolver, array $opts = [])
    {
        $resolver->setDefined([
            'startDate',
            'endDate',
            'scope',
            'scopeValue',
            'scope2',
            'scope2Value',
            'grouping'
        ]);
    }
}
