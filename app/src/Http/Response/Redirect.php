<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Http\Response;

use function http_response_code;

readonly class Redirect implements ResponseInterface
{
    public function __construct(private string $path, private int $status = 302)
    {
    }

    public function send(): never
    {
        http_response_code($this->status);
        header('Location: ' . $this->path);
        exit;
    }
}