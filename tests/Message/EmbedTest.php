<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Discord\Message\Embed;

final class EmbedTest extends TestCase
{
    public function testFluentBuilder(): void
    {
        $embed = (new Embed())
            ->title('Server Alert')
            ->description('CPU usage is critical')
            ->color(0xFF0000)
            ->field('Server', 'web-01', true)
            ->field('CPU', '95%', true)
            ->footer('Monitoring Bot')
            ->timestamp('2026-02-24T06:00:00Z');

        $result = $embed->toArray();

        $this->assertSame('Server Alert', $result['title']);
        $this->assertSame('CPU usage is critical', $result['description']);
        $this->assertSame(0xFF0000, $result['color']);
        $this->assertCount(2, $result['fields']);
        $this->assertSame('web-01', $result['fields'][0]['value']);
        $this->assertTrue($result['fields'][0]['inline']);
        $this->assertSame('Monitoring Bot', $result['footer']['text']);
        $this->assertSame('2026-02-24T06:00:00Z', $result['timestamp']);
    }

    public function testEmbedWithImage(): void
    {
        $embed = (new Embed())
            ->title('Photo')
            ->image('https://example.com/photo.jpg')
            ->thumbnail('https://example.com/thumb.jpg');

        $result = $embed->toArray();

        $this->assertSame('https://example.com/photo.jpg', $result['image']['url']);
        $this->assertSame('https://example.com/thumb.jpg', $result['thumbnail']['url']);
    }

    public function testEmbedWithAuthor(): void
    {
        $embed = (new Embed())
            ->author('Renzo', 'https://renzojohnson.com', 'https://example.com/avatar.png');

        $result = $embed->toArray();

        $this->assertSame('Renzo', $result['author']['name']);
        $this->assertSame('https://renzojohnson.com', $result['author']['url']);
        $this->assertSame('https://example.com/avatar.png', $result['author']['icon_url']);
    }

    public function testEmbedWithUrl(): void
    {
        $embed = (new Embed())
            ->title('Link')
            ->url('https://example.com');

        $result = $embed->toArray();

        $this->assertSame('https://example.com', $result['url']);
    }
}
