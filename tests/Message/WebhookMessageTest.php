<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Discord\Message\Embed;
use RenzoJohnson\Discord\Message\WebhookMessage;

final class WebhookMessageTest extends TestCase
{
    public function testSimpleTextMessage(): void
    {
        $message = new WebhookMessage(content: 'Hello');
        $result = $message->toArray();

        $this->assertSame('Hello', $result['content']);
        $this->assertFalse($result['tts']);
        $this->assertArrayNotHasKey('embeds', $result);
    }

    public function testMessageWithEmbed(): void
    {
        $embed = (new Embed())->title('Test')->description('An embed');

        $message = new WebhookMessage(content: 'Check this:');
        $message->addEmbed($embed);
        $result = $message->toArray();

        $this->assertSame('Check this:', $result['content']);
        $this->assertCount(1, $result['embeds']);
        $this->assertSame('Test', $result['embeds'][0]['title']);
    }

    public function testMessageWithOverrides(): void
    {
        $message = new WebhookMessage(
            content: 'Alert',
            username: 'AlertBot',
            avatarUrl: 'https://example.com/bot.png',
        );
        $result = $message->toArray();

        $this->assertSame('AlertBot', $result['username']);
        $this->assertSame('https://example.com/bot.png', $result['avatar_url']);
    }
}
