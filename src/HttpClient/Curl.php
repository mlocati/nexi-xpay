<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\HttpClient;

use MLocati\Nexi\XPay\Exception\HttpRequestFailed;
use MLocati\Nexi\XPay\HttpClient;

class Curl implements HttpClient
{
    public static function isAvailable(): bool
    {
        return extension_loaded('curl');
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\HttpClient::invoke()
     */
    public function invoke(string $method, string $url, array $headers, string $rawBody): Response
    {
        $options = $this->buildOptions($method, $url, $headers, $rawBody);
        $ch = curl_init();
        if ($ch === false) {
            throw new HttpRequestFailed('curl_init() failed');
        }
        try {
            if (curl_setopt_array($ch, $options) === false) {
                $err = curl_error($ch);

                throw new HttpRequestFailed('curl_setopt_array() failed' . ($err ? ": {$err}" : ''));
            }
            $responseBody = curl_exec($ch);
            if ($responseBody === false) {
                $err = curl_error($ch);

                throw new HttpRequestFailed('curl_exec() failed' . ($err ? ": {$err}" : ''));
            }
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (!is_numeric($statusCode)) {
                $err = curl_error($ch);

                throw new HttpRequestFailed('curl_getinfo() failed' . ($err ? ": {$err}" : ''));
            }
        } finally {
            curl_close($ch);
        }

        return new Response((int) $statusCode, $responseBody);
    }

    protected function buildOptions(string $method, string $url, array $headers, string $rawBody): array
    {
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => false,
        ];
        if ($rawBody !== '') {
            $options[CURLOPT_POSTFIELDS] = $rawBody;
        }
        if ($headers !== '') {
            $join = [];
            foreach ($headers as $key => $value) {
                $join[] = "{$key}: {$value}";
            }
            $options[CURLOPT_HTTPHEADER] = $join;
        }
        switch ($method) {
            case 'GET':
                $options[CURLOPT_HTTPGET] = true;
                break;
            case 'POST':
                $options[CURLOPT_POST] = true;
                break;
            default:
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                break;
        }

        return $options;
    }
}
