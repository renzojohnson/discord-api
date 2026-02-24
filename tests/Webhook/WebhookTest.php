<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Tests\Webhook;

use PHPUnit\Framework\TestCase;
use RenzoJohnson\Discord\Webhook\InteractionVerifier;

final class WebhookTest extends TestCase
{
    public function testValidateSignatureRejectsBadInputs(): void
    {
        $this->assertFalse(InteractionVerifier::validateSignature('body', '', '123', 'key'));
        $this->assertFalse(InteractionVerifier::validateSignature('body', 'sig', '', 'key'));
        $this->assertFalse(InteractionVerifier::validateSignature('body', 'sig', '123', ''));
    }
}
