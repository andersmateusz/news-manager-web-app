<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Entity;

class User
{
    private string $username;
    private string $hash;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;
        return $this;
    }
}