<?php

namespace App\Database\Entity;

use App\Database\Entity\Trait\Authenticates;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`user`")
 */
class User
{
    use Authenticates;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class)
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private Role $role;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $phone;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private ?int $ts;

    /**
     * @var Collection|Profile[] $profiles
     * @ORM\OneToMany(targetEntity=Profile::class, mappedBy="user")
     */
    private $profiles;

    public function __construct()
    {
        $this->profiles = new ArrayCollection();
        $this->ts = time();
    }

    public function getAuthIdentifierName(): string
    {
        return "id";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getTs(): ?int
    {
        return $this->ts;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        $this->role = $role;
        return $this;
    }
}
