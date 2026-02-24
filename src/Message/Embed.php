<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Message;

final class Embed
{
    private array $data = [];

    public function title(string $title): self
    {
        $this->data['title'] = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function url(string $url): self
    {
        $this->data['url'] = $url;

        return $this;
    }

    public function color(int $color): self
    {
        $this->data['color'] = $color;

        return $this;
    }

    public function timestamp(string $iso8601): self
    {
        $this->data['timestamp'] = $iso8601;

        return $this;
    }

    public function footer(string $text, ?string $iconUrl = null): self
    {
        $footer = ['text' => $text];

        if ($iconUrl !== null) {
            $footer['icon_url'] = $iconUrl;
        }

        $this->data['footer'] = $footer;

        return $this;
    }

    public function image(string $url): self
    {
        $this->data['image'] = ['url' => $url];

        return $this;
    }

    public function thumbnail(string $url): self
    {
        $this->data['thumbnail'] = ['url' => $url];

        return $this;
    }

    public function author(string $name, ?string $url = null, ?string $iconUrl = null): self
    {
        $author = ['name' => $name];

        if ($url !== null) {
            $author['url'] = $url;
        }

        if ($iconUrl !== null) {
            $author['icon_url'] = $iconUrl;
        }

        $this->data['author'] = $author;

        return $this;
    }

    public function field(string $name, string $value, bool $inline = false): self
    {
        $this->data['fields'][] = [
            'name' => $name,
            'value' => $value,
            'inline' => $inline,
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
