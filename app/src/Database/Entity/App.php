<?php

namespace App\Database\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`app`")
 */
class App
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

    /**
     * @var Collection|AppData[] $appDataList
     * @ORM\OneToMany(
     *     targetEntity=AppData::class,
     *     mappedBy="app",
     *     cascade={"persist", "remove"},
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     */
    private $appDataList;

    public function __construct()
    {
        $this->appDataList = new ArrayCollection();
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
