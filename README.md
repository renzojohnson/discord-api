# Discord API for PHP

[![Latest Version](https://img.shields.io/packagist/v/renzojohnson/discord-api.svg)](https://packagist.org/packages/renzojohnson/discord-api)
[![PHP Version](https://img.shields.io/packagist/php-v/renzojohnson/discord-api.svg)](https://packagist.org/packages/renzojohnson/discord-api)
[![License](https://img.shields.io/packagist/l/renzojohnson/discord-api.svg)](https://github.com/renzojohnson/discord-api/blob/main/LICENSE)

Lightweight PHP wrapper for [Discord REST API](https://discord.com/developers/docs/reference) v10. Zero dependencies.

Send messages, rich embeds, and files via webhooks and Bot tokens. Ed25519 interaction signature verification.

**Author:** [Renzo Johnson](https://renzojohnson.com)

## Requirements

- PHP 8.4+
- ext-curl
- ext-json
- ext-sodium (for interaction verification)

## Installation

```bash
composer require renzojohnson/discord-api
```

## Quick Start

```php
use RenzoJohnson\Discord\Discord;

$discord = new Discord('your-bot-token');

// Send a text message to a channel
$discord->sendMessage('CHANNEL_ID', 'Hello from PHP!');
```

## Bot API

### Send Message

```php
$discord->sendMessage('CHANNEL_ID', 'Hello World');
```

### Send Embed

```php
use RenzoJohnson\Discord\Message\Embed;

$embed = (new Embed())
    ->title('Server Alert')
    ->description('CPU usage is critical')
    ->color(0xFF0000)
    ->field('Server', 'web-01', inline: true)
    ->field('CPU', '95%', inline: true)
    ->footer('Monitoring Bot')
    ->timestamp(date('c'));

$discord->sendEmbed('CHANNEL_ID', $embed);

// Embed with text
$discord->sendEmbed('CHANNEL_ID', $embed, content: 'Attention!');
```

### Edit Message

```php
$discord->editMessage('CHANNEL_ID', 'MESSAGE_ID', 'Updated content');
```

### Delete Message

```php
$discord->deleteMessage('CHANNEL_ID', 'MESSAGE_ID');
```

### Add Reaction

```php
$discord->addReaction('CHANNEL_ID', 'MESSAGE_ID', '👍');
```

### Get Channel / Guild Channels

```php
$channel = $discord->getChannel('CHANNEL_ID');
$channels = $discord->getGuildChannels('GUILD_ID');
```

## Webhooks

No bot token needed — just a webhook URL.

```php
use RenzoJohnson\Discord\Webhook\Webhook;

$webhook = new Webhook('https://discord.com/api/webhooks/ID/TOKEN');

// Simple text
$webhook->send('Deploy complete!');

// Rich embed
use RenzoJohnson\Discord\Message\Embed;

$embed = (new Embed())
    ->title('Deployed v2.1.0')
    ->description('All systems operational')
    ->color(0x00FF00)
    ->footer('CI/CD Pipeline');

$webhook->sendEmbed($embed, content: 'Production deploy finished');
```

### Webhook Message with Overrides

```php
use RenzoJohnson\Discord\Message\WebhookMessage;

$message = new WebhookMessage(
    content: 'Alert!',
    username: 'AlertBot',
    avatarUrl: 'https://example.com/bot.png',
);

$embed = (new Embed())->title('Error')->description('Database connection failed')->color(0xFF0000);
$message->addEmbed($embed);

$webhook->send($message);
```

## Embed Builder

Fluent API for building rich embeds.

```php
$embed = (new Embed())
    ->title('Title')
    ->description('Description text')
    ->url('https://example.com')
    ->color(0x5865F2)              // Discord blurple
    ->author('Bot Name', 'https://example.com', 'https://example.com/icon.png')
    ->thumbnail('https://example.com/thumb.png')
    ->image('https://example.com/image.png')
    ->field('Field 1', 'Value 1', inline: true)
    ->field('Field 2', 'Value 2', inline: true)
    ->footer('Footer text', 'https://example.com/footer-icon.png')
    ->timestamp(date('c'));
```

## Interaction Verification (Ed25519)

For Discord slash commands and interactions endpoint.

```php
use RenzoJohnson\Discord\Webhook\InteractionVerifier;

// Verify signature and get body
$body = InteractionVerifier::verify('YOUR_PUBLIC_KEY');
$interaction = json_decode($body, true);

// Respond to PING
if ($interaction['type'] === 1) {
    InteractionVerifier::respondToPing();
}
```

## Error Handling

```php
use RenzoJohnson\Discord\Exception\AuthenticationException;
use RenzoJohnson\Discord\Exception\RateLimitException;
use RenzoJohnson\Discord\Exception\DiscordException;

try {
    $discord->sendMessage('CHANNEL_ID', 'Hello');
} catch (AuthenticationException $e) {
    // Invalid bot token (401)
} catch (RateLimitException $e) {
    $retryAfter = $e->getRetryAfter(); // seconds (float)
} catch (DiscordException $e) {
    // Other API errors
    $errorData = $e->getErrorData();
}
```

## Testing

```bash
composer install
vendor/bin/phpunit
```

## Links

- [Packagist](https://packagist.org/packages/renzojohnson/discord-api)
- [GitHub](https://github.com/renzojohnson/discord-api)
- [Issues](https://github.com/renzojohnson/discord-api/issues)
- [Author](https://renzojohnson.com)

## License

MIT License. Copyright (c) 2026 [Renzo Johnson](https://renzojohnson.com).
