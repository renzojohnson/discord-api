<?php

declare(strict_types=1);

namespace RenzoJohnson\Discord\Exception;

use RuntimeException;

class DiscordException extends RuntimeException
{
    private array $errorData;

    public function __construct(string $message = '', int $code = 0, array $errorData = [], ?\Throwable $previous = null)
    {
        $this->errorData = $errorData;
        parent::__construct($message, $code, $previous);
    }

    public function getErrorData(): array
    {
        return $this->errorData;
    }
}
