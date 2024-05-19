<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Dictionary;

use ReflectionClass;

/**
 * List of currencies supported by Nexi.
 *
 * @see https://ecommerce.nexi.it/specifiche-tecniche/codicebase/avviopagamento.html
 */
class Currency
{
    /**
     * Euro
     *
     * @var string
     */
    const ID_EUR = 'EUR';

    /**
     * @return string[]
     */
    public function getAvailableIDs(): array
    {
        $result = [];
        $class = new ReflectionClass($this);
        foreach ($class->getConstants() as $name => $value) {
            if (strpos($name, 'ID_') === 0 && is_string($value)) {
                $result[] = $value;
            }
        }

        return $result;
    }
}
