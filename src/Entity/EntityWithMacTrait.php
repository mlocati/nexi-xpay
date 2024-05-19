<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity;

use MLocati\Nexi\XPay\Configuration;
use MLocati\Nexi\XPay\Exception;
use stdClass;

trait EntityWithMacTrait
{
    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity\EntityWithMac
     */
    public function sign(Configuration $configuration): stdClass
    {
        $data = clone $this->_getRawData();
        $aliasFieldName = $this->getAliasFieldName();
        if ($aliasFieldName !== '' && !property_exists($data, $aliasFieldName)) {
            $data->{$aliasFieldName} = $configuration->getAlias();
        }
        ;
        $macFieldName = $this->getMacFieldName();
        $data->{$macFieldName} = $this->calculateMac($configuration);

        return $data;
    }

    /**
     * @throws \MLocati\Nexi\XPay\Exception\MissingField if the MAC field is missing or it's an empty string
     * @throws \MLocati\Nexi\XPay\Exception\WrongFieldType if the MAC field is not a string
     * @throws \MLocati\Nexi\XPay\Exception\MacMismatch if the MAC is not valid
     */
    public function checkMac(Configuration $configuration): void
    {
        /** @var \MLocati\Nexi\XPay\Entity $this */
        $macField = $this->getMacFieldName();
        $actualMac = $this->_getString($macField, true);
        if ($actualMac === '') {
            throw new Exception\MissingField($macField);
        }
        $expectedMac = $this->calculateMac($configuration);
        if ($actualMac !== $expectedMac) {
            throw new Exception\MacMismatch($this, $expectedMac, $actualMac);
        }
    }

    protected function getAliasFieldName(): string
    {
        return '';
    }

    protected function getMacFieldName(): string
    {
        return 'mac';
    }

    abstract protected function getFieldsForMac(Configuration $configuration): array;

    protected function calculateMac(Configuration $configuration): string
    {
        $str = '';
        foreach ($this->getFieldsForMac($configuration) as $field => $value) {
            $str .= "{$field}={$value}";
        }
        $str .= $configuration->getMacKey();

        return sha1($str);
    }
}
