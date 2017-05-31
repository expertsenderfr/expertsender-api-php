<?php

namespace ExpertSenderFr\ExpertSenderApi\Model;

/**
 * Class SignalSpamReport
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
