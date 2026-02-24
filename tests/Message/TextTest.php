<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Tests\Message;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Discord\Message\Text;

final class TextTest extends TestCase
{
    public function testToArrayBasic(): void
    {
        $message = new Text('Hello Discord');
        $result = $message->toArray();

        $this->assertSame('Hello Discord', $result['content']);
        $this->assertFalse($result['tts']);
    }

    public function testTtsEnabled(): void
    {
        $message = new Text('Alert!', tts: true);
        $result = $message->toArray();

        $this->assertTrue($result['tts']);
    }
}
