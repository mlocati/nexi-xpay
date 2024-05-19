<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Exception;

use MLocati\Nexi\XPay\Entity;
use MLocati\Nexi\XPay\Exception;

/**
 * Exception thrown when a field is not of an expected type.
 */
class MacMismatch extends Exception
{
    /**
     * @var \MLocati\Nexi\XPay\Entity
     */
    private $entity;

    /**
     * @var string
     */
    private $expectedMac;

    /**
     * @var string
     */
    private $actualMac;

    public function __construct(Entity $entity, string $expectedMac, string $actualMac)
    {
        $this->entity = $entity;
        $this->expectedMac = $expectedMac;
        $this->actualMac = $actualMac;
        parent::__construct("Invalid MAC (expected: {$this->expectedMac}, actual: {$this->actualMac})");
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function getExpectedMac(): string
    {
        return $this->expectedMac;
    }

    public function getActualMac(): string
    {
        return $this->actualMac;
    }
}
