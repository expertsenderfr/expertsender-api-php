<?php

namespace ExpertSenderFr\ExpertSenderApi\Test;

use ExpertSenderFr\ExpertSenderApi\ApiRequest;
use ExpertSenderFr\ExpertSenderApi\ExpertSenderClient;

class TestClient extends ExpertSenderClient
{
    protected $nextRequest;

    protected function createRequest($url, array $parameters, $method, $content)
    {
        return $this->nextRequest;
    }

    public function setNextRequest(ApiRequest $request)
    {
        $this->nextRequest = $request;
    }
}
