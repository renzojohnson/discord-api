<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Message;

final class WebhookMessage
{
    /** @var Embed[] */
    private array $embeds = [];

    public function __construct(
        private ?string $content = null,
        private ?string $username = null,
        private ?string $avatarUrl = null,
        private bool $tts = false,
    ) {}

    public function addEmbed(Embed $embed): self
    {
        $this->embeds[] = $embed;

        return $this;
    }

    public function toArray(): array
    {
        $payload = [
            'tts' => $this->tts,
        ];

        if ($this->content !== null) {
            $payload['content'] = $this->content;
        }

        if ($this->username !== null) {
            $payload['username'] = $this->username;
        }

        if ($this->avatarUrl !== null) {
            $payload['avatar_url'] = $this->avatarUrl;
        }

        if ($this->embeds !== []) {
            $payload['embeds'] = array_map(
                static fn (Embed $embed) => $embed->toArray(),
                $this->embeds,
            );
        }

        return $payload;
    }
}
