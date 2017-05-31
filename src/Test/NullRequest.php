<?php
/**
 * Created by PhpStorm.
 * User: TDF 006
 * Date: 31/05/2017
 * Time: 13:12
 */

namespace ExpertSenderFr\ExpertSenderApi\Test;


use ExpertSenderFr\ExpertSenderApi\ApiRequest;

class NullRequest extends ApiRequest
{
    public function __construct()
    {
    }

    public function send()
    {
        return new NullResponse();
    }
}
