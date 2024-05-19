<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity\ErrorResponse;

use MLocati\Nexi\XPay\Entity;

class Details extends Entity
{
    /**
     * Payment method identification code.
     */
    public function getCodice(): ?int
    {
        return $this->_getInt('codice');
    }
    
    /**
     * Payment method name.
     */
    public function getMessaggio(): ?string
    {
        return $this->_getString('messaggio');
    }
    
    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return [
            'codice',
            'messaggio',
        ];
    }
}
