<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity;

use MLocati\Nexi\XPay\Configuration;

class ErrorResponse extends Response implements EntityWithMac
{
    use EntityWithMacTrait;

    /**
     * Operation identifier assigned by XPay.
     */
    public function getIdOperazione(): ?string
    {
        return $this->_getString('idOperazione');
    }
    
    /**
     * Timestamp in millisecond format.
     */
    public function getTimeStamp(): ?int
    {
        return $this->_getInt('timeStamp');
    }

    public function getErrore(): ?ErrorResponse\Details
    {
        return $this->_getEntity('errore', ErrorResponse\Details::class);
    }

    protected function getFieldsForMac(Configuration $configuration): array
    {
        return [
            'esito' => $this->getEsito(),
            'idOperazione' => $this->getIdOperazione(),
            'timestamp' => $this->getTimeStamp(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     * @see \MLocati\Nexi\XPay\Entity\Response::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return array_merge(parent::getRequiredFields(), [
            'idOperazione',
            'timeStamp',
            'errore',
        ]);
    }
}
