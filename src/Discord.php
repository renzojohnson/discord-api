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

namespace RenzoJohnson\Discord;

use RenzoJohnson\Discord\Http\Client;
use RenzoJohnson\Discord\Message\Embed;
use RenzoJohnson\Discord\Message\Text;

final class Discord
{
    private Client $client;

    public function __construct(
        string $botToken,
        int $timeout = 30,
    ) {
        $this->client = new Client($botToken, $timeout);
    }

    public function sendMessage(string $channelId, string $content, bool $tts = false): array
    {
        $message = new Text($content, $tts);

        return $this->client->post('/channels/' . $channelId . '/messages', $message->toArray());
    }

    public function sendEmbed(string $channelId, Embed $embed, ?string $content = null): array
    {
        $payload = [
            'embeds' => [$embed->toArray()],
        ];

        if ($content !== null) {
            $payload['content'] = $content;
        }

        return $this->client->post('/channels/' . $channelId . '/messages', $payload);
    }

    public function editMessage(string $channelId, string $messageId, string $content): array
    {
        return $this->client->patch(
            '/channels/' . $channelId . '/messages/' . $messageId,
            ['content' => $content],
        );
    }

    public function deleteMessage(string $channelId, string $messageId): array
    {
        return $this->client->delete('/channels/' . $channelId . '/messages/' . $messageId);
    }

    public function addReaction(string $channelId, string $messageId, string $emoji): array
    {
        $encoded = urlencode($emoji);

        return $this->client->put(
            '/channels/' . $channelId . '/messages/' . $messageId . '/reactions/' . $encoded . '/@me',
        );
    }

    public function getChannel(string $channelId): array
    {
        return $this->client->get('/channels/' . $channelId);
    }

    public function getGuildChannels(string $guildId): array
    {
        return $this->client->get('/guilds/' . $guildId . '/channels');
    }
}
