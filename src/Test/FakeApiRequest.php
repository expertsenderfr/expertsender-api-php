<?php

namespace TDF\ExpertSenderApi\Test;

use TDF\ExpertSenderApi\ApiRequest;
use TDF\ExpertSenderApi\ApiResponse;

class FakeApiRequest extends ApiRequest
{
    public function __construct()
    {
    }

    private $response;

    public function setResponse(ApiResponse $response)
    {
        $this->response = $response;
    }

    public function send()
    {
        return $this->response;
    }
}
