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

namespace RenzoJohnson\Discord\Webhook;

use RenzoJohnson\Discord\Exception\DiscordException;

final class InteractionVerifier
{
    public static function verify(string $publicKey): string
    {
        $signature = $_SERVER['HTTP_X_SIGNATURE_ED25519'] ?? '';
        $timestamp = $_SERVER['HTTP_X_SIGNATURE_TIMESTAMP'] ?? '';
        $body = file_get_contents('php://input');

        if ($body === false || $body === '') {
            throw new DiscordException('Empty request body');
        }

        if ($signature === '' || $timestamp === '') {
            throw new DiscordException('Missing Discord signature headers');
        }

        if (!self::validateSignature($body, $signature, $timestamp, $publicKey)) {
            http_response_code(401);
            throw new DiscordException('Invalid signature');
        }

        return $body;
    }

    public static function validateSignature(
        string $body,
        string $signature,
        string $timestamp,
        string $publicKey,
    ): bool {
        if ($signature === '' || $timestamp === '' || $publicKey === '') {
            return false;
        }

        $message = $timestamp . $body;
        $sigBin = sodium_hex2bin($signature);
        $keyBin = sodium_hex2bin($publicKey);

        return sodium_crypto_sign_verify_detached($sigBin, $message, $keyBin);
    }

    public static function respondToPing(): never
    {
        header('Content-Type: application/json');
        echo json_encode(['type' => 1], JSON_THROW_ON_ERROR);
        exit;
    }
}
