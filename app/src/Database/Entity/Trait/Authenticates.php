<?php

namespace App\Database\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait Authenticates
{
    /** @ORM\Column(type="string", nullable=false) */
    private ?string $password;

    /** @ORM\Column(type="string", name="remember_token", nullable=true) */
    private ?string $rememberToken;

    abstract public function getAuthIdentifierName(): string;

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken(): string
    {
        return $this->rememberToken ?? "";
    }

    public function setRememberToken($value): static
    {
        $this->rememberToken = $value;
        return $this;
    }

    public function getRememberTokenName(): string
    {
        return "remember_token";
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
}
