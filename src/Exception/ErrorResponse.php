<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Exception;

use MLocati\Nexi\XPay\Exception;
use MLocati\Nexi\XPay\Entity\ErrorResponse as Entity;

/**
 * Exception thrown when an HTTP request can't be performed.
 * Use the getCode() method to retrieve the HTTP response code (usually between 400 and 599).
 */
class ErrorResponse extends Exception
{
    /**
     * @var \MLocati\Nexi\XPay\Entity\ErrorResponse
     */
    private $entity;

    public function __construct(Entity $response)
    {
        $details = $response->getErrore();
        parent::__construct(($details === null ? '' : $details->getMessaggio()) ?: 'Unknown error', $details === null ? 0 : $details->getCodice());
    }

    public function getResponse(): Entity
    {
        return $this->entity;
    }
}
