<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Exception;

use MLocati\Nexi\XPay\Exception;

/**
 * Exception thrown when an HTTP request can't be performed.
 */
class HttpRequestFailed extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
