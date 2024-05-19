<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity\SimplePay\Callback\Customer;

use MLocati\Nexi\XPay\Entity\Response;
use stdClass;

class Cancel extends Response
{
    public function __construct(stdClass $data)
    {
        if (is_string($data->importo ?? null) && is_numeric($data->importo)) {
            $data->importo = (int) $data->importo;
        }
        parent::__construct($data);
    }

    public static function fromCustomerRequest(array $getParameters = null): self
    {
        return new self((object) ($getParameters === null ? $_GET : $getParameters));
    }
    
    /**
     * Merchant profile identification code (fixed value communicated by Nexi during the activation phase).
     */
    public function getAlias(): ?string
    {
        return $this->_getString('alias');
    }

    /**
     * Payment identification code composed.
     * The code must be unique for each authorization request, only in the event of a negative outcome of the authorization can the merchant re-submit the same request with the same codTrans another 2 times.
     * During the configuration phase the merchant can choose to reduce the 3 attempts.
     */
    public function getCodTrans(): ?string
    {
        return $this->_getString('codTrans');
    }

    /**
     * Amount to be authorized expressed in cents without separator.
     * The first 2 numbers on the right represent the cents.
     */
    public function getImporto(): ?int
    {
        return $this->_getInt('importo');
    }

    /**
     * Amount to be authorized, with separator.
     *
     * Required
     * Minimum: 0
     * Maximum: 999999.99
     *
     * @example '50.00' corresponds to 50.00
     */
    public function getImportoAsDecimal(): string
    {
        $value = (string) $this->getImporto();
        if ($value === '') {
            return '';
        }
        if (strlen($value) < 3) {
            $value = substr('00' . $value, -3);
        }
        $integers = substr($value, 0, -2);
        $decimals = substr($value, -2);

        return $integers . '.' . $decimals;
    }

    /**
     * The code of the currency in which the amount is expressed.
     * EUR (Euro) or 978 (Euro)
     * Only value allowed: EUR (ISO 4217 Alphabetic for Euro) or 978 (ISO 4217 Numeric for Euro)
     */
    public function getDivisa(): ?string
    {
        return $this->_getString('divisa');
    }

    /**
     * {@inheritdoc}
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return [
            'codTrans',
            'importo',
            'esito',
        ];
    }
}
