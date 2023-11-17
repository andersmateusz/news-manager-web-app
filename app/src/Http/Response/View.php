<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Http\Response;

use function header;
use function http_response_code;
use function ob_get_clean;
use function ob_start;
use function sprintf;

readonly class View implements ResponseInterface
{
    public function __construct(private string $template, private array $data = [], private int $status = 200)
    {
    }

    public function send(): never
    {
        header('Content-Type: text/html; charset=utf-8');
        http_response_code($this->status);
        ob_start();
        $data = $this->data;
        include __DIR__ . sprintf('/../../../views/%s', $this->template);
        echo ob_get_clean();
        exit;
    }
}