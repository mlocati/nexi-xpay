<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay;

use MLocati\Nexi\XPay\Entity\EntityWithMac;
use MLocati\Nexi\XPay\Entity\ErrorResponse;
use stdClass;

class Client
{
    /**
     * @var \MLocati\Nexi\XPay\Configuration
     */
    protected $configuration;

    /**
     * @var \MLocati\Nexi\XPay\HttpClient
     */
    protected $httpClient;

    /**
     * @throws \MLocati\Nexi\XPay\Exception\NoHttpClient if $httpClient is NULL and no HTTP client is available
     */
    public function __construct(
        Configuration $configuration,
        ?HttpClient $httpClient = null
    ) {
        $this->configuration = $configuration;
        $this->httpClient = $httpClient ?? $this->buildHttpClient();
    }

    public function sign(EntityWithMac $entity): stdClass
    {
        return $entity->sign($this->configuration);
    }

    /**
     * @param string $platform Name of the CMS from which the call is being made, used by Nexi for statistical purposes. If you are not using a particular CMS, use 'custom'.
     * @param string $platformVers Version of the CMS from which the call is being made, used by Nexi for statistical purposes. If you are not using a particular CMS, use '0'.
     * @param string $pluginVers Version of the CMS from which the call is being made, used by Nexi for statistical purposes. If you are not using a particular CMS, use '0'.
     *
     * @throws \MLocati\Nexi\XPay\Exception\MissingField
     * @throws \MLocati\Nexi\XPay\Exception\HttpError
     * @throws \MLocati\Nexi\XPay\Exception\InvalidJson
     */
    public function listSupportedPaymentMethods(Entity\PaymentMethods\Request $request): Entity\PaymentMethods\Response
    {
        $request->checkRequiredFields();
        $url = $this->buildUrl('/ecomm/api/profileInfo');
        $response = $this->invoke('POST', $url, $request);
        $responseData = $this->decodeJsonToObject($response->getBody());

        return $this->unserializeEntity($responseData, Entity\PaymentMethods\Response::class);
    }

    public function getSimplePaySubmitUrl(): string
    {
        return $this->buildUrl('/ecomm/ecomm/DispatcherServlet');
    }

    /**
     * @throws \MLocati\Nexi\XPay\Exception\NoHttpClient
     */
    protected function buildHttpClient(): HttpClient
    {
        if (HttpClient\Curl::isAvailable()) {
            return new HttpClient\Curl();
        }
        if (HttpClient\StreamWrapper::isAvailable()) {
            return new HttpClient\StreamWrapper();
        }
        throw new Exception\NoHttpClient();
    }

    protected function buildUrl(string $path): string
    {
        $url = rtrim($this->configuration->getBaseUrl(), '/') . '/' . ltrim($path, '/');

        return $url;
    }

    /**
     * @param \MLocati\Nexi\XPayWeb\Entity|\MLocati\Nexi\XPayWeb\Entity[]|null $requestBody
     *
     * @throws \MLocati\Nexi\XPay\Exception\HttpError
     */
    protected function invoke(string $method, string $url, $requestBody = null): HttpClient\Response
    {
        $headers = [];
        if ($requestBody === null) {
            $requestJson = '';
        } else {
            if ($requestBody instanceof EntityWithMac) {
                $requestBody = $requestBody->sign($this->configuration);
            }
            $requestJson = json_encode($requestBody, JSON_UNESCAPED_SLASHES);
            if ($requestJson === false) {
                throw new \RuntimeException('Failed to create the JSON data: ' . (json_last_error_msg() ?: 'unknown reason'));
            }
            $headers['Content-Type'] = 'application/json';
        }
        $response = $this->httpClient->invoke($method, $url, $headers, $requestJson);
        if ($response->getStatusCode() !== 200) {
            throw new Exception\HttpError($response->getStatusCode(), $response->getBody());
        }

        return $response;
    }

    /**
     * @throws \MLocati\Nexi\XPay\Exception\InvalidJson
     */
    protected function decodeJsonToObject(string $json): stdClass
    {
        $data = $this->decodeJson($json);
        if ($data instanceof stdClass) {
            return $data;
        }

        throw new Exception\InvalidJson($json, 'The JSON does NOT represent an object');
    }

    /**
     * @throws \MLocati\Nexi\XPay\Exception\ErrorResponse
     * @throws \MLocati\Nexi\XPay\Exception\MissingField
     * @throws \MLocati\Nexi\XPay\Exception\WrongFieldType
     * @throws \MLocati\Nexi\XPay\Exception\HttpError
     */
    protected function unserializeEntity(stdClass $json, string $entityName, bool $checkEsito = true): Entity
    {
        if ($checkEsito && is_object($json->errore ?? null) && in_array($json->esito ?? null, [Entity\Response::ESITO_KO], true)) {
            $entity = new ErrorResponse($json);
            throw new Exception\ErrorResponse($entity);
        }
        $entity = new $entityName($json);
        /** @var \MLocati\Nexi\XPay\Entity $entity */
        $entity->checkRequiredFields();
        if ($entity instanceof EntityWithMac) {
            $entity->checkMac($this->configuration);
        }

        return $entity;
    }

    /**
     * @throws \MLocati\Nexi\XPay\Exception\InvalidJson
     */
    private function decodeJson(string $json)
    {
        if ($json === 'null') {
            return null;
        }
        $decoded = json_decode($json);
        if ($decoded === null) {
            throw new Exception\InvalidJson($json);
        }

        return $decoded;
    }
}
