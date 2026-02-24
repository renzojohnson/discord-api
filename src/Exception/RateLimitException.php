<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Exception;

class RateLimitException extends DiscordException
{
    public function __construct(string $message = '', float $retryAfter = 0, array $errorData = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, 429, $errorData, $previous);
        $this->retryAfter = $retryAfter;
    }

    private float $retryAfter;

    public function getRetryAfter(): float
    {
        return $this->retryAfter;
    }
}
