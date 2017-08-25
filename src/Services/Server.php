<?php

namespace ExpertSenderFr\ExpertSenderApi\Services;

use DateTime;
use DateTimeImmutable;

class Server extends ApiService
{
    const SERVICE_URL = 'Api/Time';

    public function getTime($opts)
    {
        $response = $this->doAll([], $opts);
        return new DateTimeImmutable($response->Data->item(0)->textContent);
    }
}
