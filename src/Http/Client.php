<?php

/**
 * Discord API
 *
 * @package   RenzoJohnson\Discord
 * @author    Renzo Johnson <hello@renzojohnson.com>
 * @copyright 2026 Renzo Johnson
 * @license   MIT
 * @link      https://renzojohnson.com
 */

declare(strict_types=1);

namespace RenzoJohnson\Discord\Http;

use RenzoJohnson\Discord\Exception\AuthenticationException;
use RenzoJohnson\Discord\Exception\DiscordException;
use RenzoJohnson\Discord\Exception\RateLimitException;

final class Client
{
    private const BASE_URL = 'https://discord.com/api/v10';

    public function __construct(
        private readonly ?string $botToken = null,
        private readonly int $timeout = 30,
    ) {}

    public function post(string $endpoint, array $payload): array
    {
        return $this->request('POST', $endpoint, $payload);
    }

    public function patch(string $endpoint, array $payload): array
    {
        return $this->request('PATCH', $endpoint, $payload);
    }

    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    public function put(string $endpoint): array
    {
        return $this->request('PUT', $endpoint);
    }

    public function get(string $endpoint): array
    {
        return $this->request('GET', $endpoint);
    }

    public function postWebhook(string $webhookUrl, array $payload): array
    {
        $ch = curl_init($webhookUrl . '?wait=true');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_THROW_ON_ERROR),
        ]);

        return $this->execute($ch);
    }

    private function request(string $method, string $endpoint, ?array $payload = null): array
    {
        $url = self::BASE_URL . $endpoint;
        $ch = curl_init($url);

        $headers = [
            'Content-Type: application/json',
        ];

        if ($this->botToken !== null) {
            $headers[] = 'Authorization: Bot ' . $this->botToken;
        }

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($method === 'POST' && $payload !== null) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($payload, JSON_THROW_ON_ERROR);
        }

        if ($method === 'PATCH') {
            $options[CURLOPT_CUSTOMREQUEST] = 'PATCH';
            if ($payload !== null) {
                $options[CURLOPT_POSTFIELDS] = json_encode($payload, JSON_THROW_ON_ERROR);
            }
        }

        if ($method === 'PUT') {
            $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
            $options[CURLOPT_POSTFIELDS] = '';
        }

        if ($method === 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        curl_setopt_array($ch, $options);

        return $this->execute($ch);
    }

    private function execute(\CurlHandle $ch): array
    {
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new DiscordException('cURL error: ' . $error);
        }

        if ($httpCode === 204) {
            return [];
        }

        $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        if ($httpCode === 401) {
            throw new AuthenticationException(
                $decoded['message'] ?? 'Unauthorized',
                401,
                $decoded,
            );
        }

        if ($httpCode === 429) {
            throw new RateLimitException(
                $decoded['message'] ?? 'Rate limited',
                (float) ($decoded['retry_after'] ?? 0),
                $decoded,
            );
        }

        if ($httpCode >= 400) {
            throw new DiscordException(
                $decoded['message'] ?? 'API error',
                $httpCode,
                $decoded,
            );
        }

        return $decoded;
    }
}
