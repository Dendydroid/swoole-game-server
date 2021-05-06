<?php

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`role`")
 */
class Role
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
    private ?string $name;

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


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }
}
