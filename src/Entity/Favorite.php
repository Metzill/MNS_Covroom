<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavoriteRepository::class)
 */
class Favorite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $StartCity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $EndCity;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartCity(): ?string
    {
        return $this->StartCity;
    }

    public function setStartCity(string $StartCity): self
    {
        $this->StartCity = $StartCity;

        return $this;
    }

    public function getEndCity(): ?string
    {
        return $this->EndCity;
    }

    public function setEndCity(string $EndCity): self
    {
        $this->EndCity = $EndCity;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->IdUser;
    }

    public function setIdUser(?User $IdUser): self
    {
        $this->IdUser = $IdUser;

        return $this;
    }
}
