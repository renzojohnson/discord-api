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

namespace RenzoJohnson\Discord\Message;

final readonly class Text
{
    public function __construct(
        private string $content,
        private bool $tts = false,
    ) {}

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'tts' => $this->tts,
        ];
    }
}
