<?php

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`profile`")
 */
class Profile
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private ?string $username;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="profiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $ts;

    public function __construct()
    {
        $this->ts = time();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTs(): ?int
    {
        return $this->ts;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
