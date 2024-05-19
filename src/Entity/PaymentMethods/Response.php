<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity\PaymentMethods;

use MLocati\Nexi\XPay\Configuration;
use MLocati\Nexi\XPay\Entity\EntityWithMac;
use MLocati\Nexi\XPay\Entity\EntityWithMacTrait;
use MLocati\Nexi\XPay\Entity\Response as BaseResponse;

/**
 * @link https://ecommerce.nexi.it/specifiche-tecniche/apibackoffice/metodidipagamentoattivi.html
 */
class Response extends BaseResponse implements EntityWithMac
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
    
    /**
     * Nexi logo (240x60 pixels)
     */
    public function getUrlLogoNexiSmall(): ?string
    {
        return $this->_getString('urlLogoNexiSmall');
    }

    /**
     * Nexi logo (480x120 pixels)
     */
    public function getUrlLogoNexiLarge(): ?string
    {
        return $this->_getString('urlLogoNexiLarge');
    }

    /**
     * Array of objects containing information on the payment method active on the terminal.
     *
     * @return \MLocati\Nexi\XPay\Entity\PaymentMethods\Response\Method[]
     */
    public function getAvailableMethods(): array
    {
        return $this->_getEntityArray('availableMethods', Response\Method::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity\EntityWithMacTrait::getFieldsForMac()
     */
    protected function getFieldsForMac(Configuration $configuration): array
    {
        return [
            'esito' => $this->getEsito(),
            'idOperazione' => $this->getIdOperazione(),
            'timeStamp' => $this->getTimeStamp(),
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
            'urlLogoNexiSmall',
            'urlLogoNexiLarge',
            'availableMethods',
        ]);
    }
}