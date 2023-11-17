<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Http\Response;

interface ResponseInterface
{
    public function send(): never;
}