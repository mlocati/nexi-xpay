<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity;

use MLocati\Nexi\XPay\Configuration;
use stdClass;

interface EntityWithMac
{
    public function sign(Configuration $configuration): stdClass;

    /**
     * @throws \MLocati\Nexi\XPay\Exception\MissingField if the MAC field is missing or it's an empty string
     * @throws \MLocati\Nexi\XPay\Exception\WrongFieldType if the MAC field is not a string
     * @throws \MLocati\Nexi\XPay\Exception\MacMismatch if the MAC is not valid
     */
    public function checkMac(Configuration $configuration): void;
    
}
