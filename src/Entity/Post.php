<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $preview = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column]
    private ?int $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    public function getId(): ?int
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

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }
    
    public function getText(): ?string 
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;
        
        return $this;
    }
    
    public function getAuthor(): ?int
    {
        return $this->author;
    }

    public function setAuthor(int $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
