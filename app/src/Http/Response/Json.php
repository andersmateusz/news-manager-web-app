<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Http\Response;

use function header;
use function http_response_code;
use function json_encode;

readonly class Json implements ResponseInterface
{
    public function __construct(private array $data, private int $status = 200)
    {
    }

    public function send(): never
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($this->status);
        echo json_encode($this->data);
        exit;
    }
}