<?php

namespace App\Entity;

use App\Repository\RateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RateRepository::class)
 */
class Rate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Rate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="RatingWritten")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdUserRating;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="RatingReceived")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdUserRated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?int
    {
        return $this->Rate;
    }

    public function setRate(int $Rate): self
    {
        $this->Rate = $Rate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->Comment;
    }

    public function setComment(string $Comment): self
    {
        $this->Comment = $Comment;

        return $this;
    }

    public function getIdUserRating(): ?User
    {
        return $this->IdUserRating;
    }

    public function setIdUserRating(?User $IdUserRating): self
    {
        $this->IdUserRating = $IdUserRating;

        return $this;
    }

    public function getIdUserRated(): ?User
    {
        return $this->IdUserRated;
    }

    public function setIdUserRated(?User $IdUserRated): self
    {
        $this->IdUserRated = $IdUserRated;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(\DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }
}
