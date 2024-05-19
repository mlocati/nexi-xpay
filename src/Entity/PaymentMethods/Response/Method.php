<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity\PaymentMethods\Response;

use MLocati\Nexi\XPay\Entity;

class Method extends Entity
{
    /**
     * Payment circuit, such as Visa and Mastercard.
     *
     * @var string
     */
    const TYPE_PAYMENTCIRCUIT = 'CC';

    /**
     * Alternative payment method, such as PayPal and Amazon Pay.
     *
     * @var string
     */
    const TYPE_ALTERNATIVE = 'APM';

    /**
     * Payment method identification code.
     */
    public function getCode(): ?string
    {
        return $this->_getString('code');
    }

    /**
     * Payment method name.
     */
    public function getDescription(): ?string
    {
        return $this->_getString('description');
    }

    /**
     * How the "selectedcard" parameter must be set when starting the payment to be redirected directly to the checkout page of that specific payment method.
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/codicebase/avviopagamento.html
     */
    public function getSelectedcard(): ?string
    {
        return $this->_getString('selectedcard');
    }

    /**
     * URL of the payment method logo.
     */
    public function getImage(): ?string
    {
        return $this->_getString('image');
    }

    /**
     * Payment method type (see TYPE_... constants)
     */
    public function getType(): ?string
    {
        return $this->_getString('type');
    }

    /**
     * Whether recurring payments can be made to the payment method.
     * 'Y' for yes
     * 'N' for no
     */
    public function getRecurring(): ?string
    {
        return $this->_getString('recurring');
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return [
            'code',
            'description',
            'selectedcard',
            'image',
            'type',
            'recurring',
        ];
    }
}
