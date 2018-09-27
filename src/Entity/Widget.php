<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WidgetRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"widget_read"}},
 *     denormalizationContext={"groups"={"widget_write"}},
 *     collectionOperations={
 *       "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *       "post"
 *     },
 *     itemOperations={
 *       "get",
 *       "put"
 *     },
 *     attributes={"order"={"title"}}
 * )
 */
class Widget
{
    use BlameableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * @Groups({"widget_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"widget_read", "widget_write"})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="json")
     * @Groups({"widget_read", "widget_write"})
     * @Assert\NotBlank()
     */
    private $content = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }
}
