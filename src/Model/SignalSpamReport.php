<?php

namespace ExpertSenderFr\ExpertSenderApi\Model;

/**
 * Class SignalSpamReport
 *
 * @author Isaac Rozas García <isaac.rozgar@gmail.com>
 */
class SignalSpamReport
{
    public $isSummaryRow = false;
    public $delivered;
    public $complaints;
    public $complaintRate;
    public $spamTraps;
    public $provider;
}
