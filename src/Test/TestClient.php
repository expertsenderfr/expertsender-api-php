<?php

namespace TDF\ExpertSenderApi\Test;

use TDF\ExpertSenderApi\ApiRequest;
use TDF\ExpertSenderApi\ExpertSenderClient;

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
