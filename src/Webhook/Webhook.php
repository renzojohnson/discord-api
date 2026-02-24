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

use RenzoJohnson\Discord\Http\Client;
use RenzoJohnson\Discord\Message\Embed;
use RenzoJohnson\Discord\Message\WebhookMessage;

final class Webhook
{
    private Client $client;

    public function __construct(
        private readonly string $webhookUrl,
        int $timeout = 30,
    ) {
        $this->client = new Client(timeout: $timeout);
    }

    public function send(string|WebhookMessage $message): array
    {
        if (is_string($message)) {
            $message = new WebhookMessage(content: $message);
        }

        return $this->client->postWebhook($this->webhookUrl, $message->toArray());
    }

    public function sendEmbed(Embed $embed, ?string $content = null): array
    {
        $message = new WebhookMessage(content: $content);
        $message->addEmbed($embed);

        return $this->client->postWebhook($this->webhookUrl, $message->toArray());
    }
}
