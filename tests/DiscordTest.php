<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Tests;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Discord\Discord;

final class DiscordTest extends TestCase
{
    public function testInstantiation(): void
    {
        $discord = new Discord('test-bot-token');

        $this->assertInstanceOf(Discord::class, $discord);
    }
}
