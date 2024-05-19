<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Configuration;

use MLocati\Nexi\XPay\Configuration;

use RuntimeException;

class FromArray implements Configuration
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $macKey;

    /**
     * @param array $data {
     *     alias: string, // your merchant alias
     *     macKey: string, // your MAC key
     *     environment?: string, // 'test' for test environment, empty/missing for production
     *     baseUrl?: string, // if missing or empty: we'll use the default base URL for test or production
     * }
     *
     * @throws \RuntimeException in case of missing/wrong parameters
     */
    public function __construct(array $data)
    {
        $test = ($data['environment'] ?? '') === 'test';
        $this->baseUrl = (string) ($data['baseUrl'] ?? '');
        if ($this->baseUrl === '') {
            $this->baseUrl = $test ? static::DEFAULT_BASEURL_TEST : static::DEFAULT_BASEURL_PRODUCTION;
            if ($this->baseUrl === '') {
                throw new RuntimeException('Missing baseUrl in configuration');
            }
        }
        if (!filter_var($this->baseUrl, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Wrong baseUrl in configuration');
        }
        $this->alias = (string) ($data['alias'] ?? '');
        if ($this->alias === '') {
            throw new RuntimeException('Missing alias in configuration');
        }
        $this->macKey = (string) ($data['macKey'] ?? '');
        if ($this->macKey === '') {
            throw new RuntimeException('Missing macKey in configuration');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Configuration::getBaseUrl()
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Configuration::getAlias()
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Configuration::getMacKey()
     */
    public function getMacKey(): string
    {
        return $this->macKey;
    }
}
