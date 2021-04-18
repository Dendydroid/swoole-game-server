<?php

namespace App\Component\Config;

use App\Component\Abstract\Singleton;
use JetBrains\PhpStorm\Pure;

class Config extends Singleton
{
    protected string $configFolder;

    protected array $configs = [];

    public function __construct(string $configFolder = "./")
    {
        $this->configFolder = $configFolder;
    }

    public function getConfigFolder(): string
    {
        return $this->configFolder;
    }

    public function setConfigFolder(string $configFolder): static
    {
        $this->configFolder = $configFolder;
        return $this;
    }

    public function load(): static
    {
        foreach (scandir($this->configFolder) as &$fileName)
        {
            if(!in_array($fileName, [".", ".."], true))
            {
                $this->configs[substr($fileName, 0, strrpos($fileName,"."))] = include ($this->configFolder . "/" . $fileName);
            }
        }
        return $this;
    }

    public function get(string $key): array
    {
        return $this->configs[$key];
    }
}