<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay;

interface Configuration
{
    /**
     * This is the default URL to be used for tests.
     *
     * @see https://ecommerce.nexi.it/specifiche-tecniche/codicebase.html
     *
     * @var string
     */
    const DEFAULT_BASEURL_TEST = 'https://int-ecommerce.nexi.it/';

    /**
     * This is the default URL to be used in production.
     *
     * @see https://ecommerce.nexi.it/specifiche-tecniche/codicebase.html
     *
     * @var string
     */
    const DEFAULT_BASEURL_PRODUCTION = 'https://ecommerce.nexi.it/';

    /**
     * Get the API key.
     */
    public function getBaseUrl(): string;

    /**
     * Get the merchant alias.
     */
    public function getAlias(): string;

    /**
     * Get the merchant Message Code Authentication key.
     */
    public function getMacKey(): string;
}
