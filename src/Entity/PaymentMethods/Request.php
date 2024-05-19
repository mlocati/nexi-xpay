<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity\PaymentMethods;

use MLocati\Nexi\XPay\Entity;
use MLocati\Nexi\XPay\Configuration;
use MLocati\Nexi\XPay\Entity\EntityWithMac;
use MLocati\Nexi\XPay\Entity\EntityWithMacTrait;

class Request extends Entity implements EntityWithMac
{
    use EntityWithMacTrait;

    /**
     * Name of the CMS from which the call is being made, used by Nexi for statistical purposes.
     * If you are not using a particular CMS, use 'custom'.
     */
    public function getPlatform(): ?string
    {
        return $this->_getString('platform');
    }

    /**
     * Name of the CMS from which the call is being made, used by Nexi for statistical purposes.
     * If you are not using a particular CMS, use 'custom'.
     *
     * @return $this
     */
    public function setPlatform(string $value): self
    {
        return $value === '' ? $this->_unset('platform') : $this->_set('platform', $value);
    }

    /**
     * Version of the CMS from which the call is being made, used by Nexi for statistical purposes.
     * If you are not using a particular CMS, use '0'.
     */
    public function getPlatformVers(): ?string
    {
        return $this->_getString('platformVers');
    }

    /**
     * Version of the CMS from which the call is being made, used by Nexi for statistical purposes.
     * If you are not using a particular CMS, use '0'.
     *
     * @return $this
     */
    public function setPlatformVers(string $value): self
    {
        return $value === '' ? $this->_unset('platformVers') : $this->_set('platformVers', $value);
    }

    /**
     * Version of the CMS plugin from which you are making the call, used by Nexi for statistical purposes.
     * If you are not using a particular CMS, enter '0'.
     */
    public function getPluginVers(): ?string
    {
        return $this->_getString('pluginVers');
    }

    /**
     * Version of the CMS plugin from which you are making the call, used by Nexi for statistical purposes.
     * If you are not using a particular CMS, enter '0'.
     *
     * @return $this
     */
    public function setPluginVers(string $value): self
    {
        return $value === '' ? $this->_unset('pluginVers') : $this->_set('pluginVers', $value);
    }

    /**
     * Timestamp in millisecond format.
     */
    public function getTimeStamp(): ?int
    {
        return $this->_getInt('timeStamp');
    }

    /**
     * Timestamp in millisecond format.
     *
     * @return $this
     */
    public function setTimeStamp(int $value): self
    {
        return $this->_set('timeStamp', $value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity\EntityWithMacTrait::getAliasFieldName()
     */
    protected function getAliasFieldName(): string
    {
        return 'apiKey';
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity\EntityWithMacTrait::getFieldsForMac()
     */
    protected function getFieldsForMac(Configuration $configuration): array
    {
        return [
            'apiKey' => $configuration->getAlias(),
            'timeStamp' => $this->getTimeStamp(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return [
            'platform',
            'platformVers',
            'pluginVers',
            'timeStamp',
        ];
    }
}