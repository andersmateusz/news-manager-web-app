<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Http;

use RuntimeException;

class HttpException extends RuntimeException
{
    public function __construct(string $message = "", int $code = 0, private readonly array $headers = [])
    {
        parent::__construct($message, $code);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}