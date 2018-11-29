<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Yaml\Yaml;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WidgetContextRepository")
 * @UniqueEntity("name")
 */
class WidgetContext
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $host;

    /**
     * @ORM\Column(type="text")
     */
    private $configuration;

    private $data;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getConfiguration(): ?string
    {
        return $this->configuration;
    }

    public function setConfiguration(string $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function getData()
    {
        if (null === $this->data) {
            $this->data = Yaml::parse($this->getConfiguration())
                + [
                    'name' => $this->getName(),
                ];
        }

        return $this->data;
    }

    public function get($name, $default = null)
    {
        $data = $this->getData();

        return $data[$name] ?? $default;
    }

    public function getLabel()
    {
        return $this->getData()['label'] ?? self::class;
    }
}
