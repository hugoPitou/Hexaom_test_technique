<?php

namespace App\Entity;

use App\Repository\FileManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileManagerRepository::class)]
class FileManager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFillname(): ?string
    {
        return $this->filename;
    }

    public function setFillname(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }
}
