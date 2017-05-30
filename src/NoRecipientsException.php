<?php

namespace TDF\ExpertSenderApi;

use Exception;

class NoRecipientsException extends \Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if ($message === "") {
            $message = 'At least one type of recipient is required.';
        }

        parent::__construct($message, $code, $previous);
    }
}
