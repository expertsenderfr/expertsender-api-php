<?php

namespace ExpertSenderFr\ExpertSenderApi\Test;

use ExpertSenderFr\ExpertSenderApi\ApiRequest;
use ExpertSenderFr\ExpertSenderApi\ApiResponse;

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
