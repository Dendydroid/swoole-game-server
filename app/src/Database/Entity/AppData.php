<?php

namespace App\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="`app_data`",
 *     indexes={
 *         @ORM\Index(name="app_data_app_id_key", columns={"app_id", "key"})
 *     }
 * )
 */
class AppData
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", name="`key`")
     */
    private ?string $key;

    /**
     * @ORM\ManyToOne(targetEntity=App::class, inversedBy="appDataList")
     * @ORM\JoinColumn(name="app_id", referencedColumnName="id")
     */
    private App $app;

    /**
     * @ORM\Column(type="string", name="`value`")
     */
    private ?string $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): static
    {
        $this->key = $key;
        return $this;
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function setApp(App $app): static
    {
        $this->app = $app;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;
        return $this;
    }
}
