<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Http;

use Andersma\NewsManager\EnumFromNameTrait;

enum HttpMethod
{
    use EnumFromNameTrait;
    case GET;
    case POST;
    case PATCH;
    case DELETE;
    case PUT;

    public static function fromGlobal(): ?self
    {
        return match ($_SERVER['REQUEST_METHOD']) {
            'GET' => self::GET,
            'POST' => self::POST,
            'PATCH' => self::PATCH,
            'DELETE' => self::DELETE,
            'PUT' => self::PUT,
            default => null,
        };
    }
}