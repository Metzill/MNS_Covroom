<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TravelRepository::class)
 */
class Travel
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
    private $SeatAtTheBegining;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdCar;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Travels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdUser;

    /**
     * @ORM\Column(type="float")
     */
    private $StartLatitude;

    /**
     * @ORM\Column(type="float")
     */
    private $StartLongitude;

    /**
     * @ORM\Column(type="float")
     */
    private $EndLatitude;

    /**
     * @ORM\Column(type="float")
     */
    private $EndLongitude;

    /**
     * @ORM\Column(type="datetime")
     */
    private $StartTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $EndTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $StartCity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $EndCity;

    /**
     * @ORM\ManyToMany(targetEntity=TravelPreference::class, inversedBy="travel")
     */
    private $TravelPreferences;

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

    /**
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="Travel", orphanRemoval=true)
     */
    private $alerts;

    /**
     * @ORM\OneToMany(targetEntity=Seat::class, mappedBy="IdTravel", orphanRemoval=true)
     */
    private $seats;

    public function __construct()
    {
        $this->TravelPreferences = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeatAtTheBegining(): ?int
    {
        return $this->SeatAtTheBegining;
    }

    public function setSeatAtTheBegining(int $SeatAtTheBegining): self
    {
        $this->SeatAtTheBegining = $SeatAtTheBegining;

        return $this;
    }

    public function getIdCar(): ?Car
    {
        return $this->IdCar;
    }

    public function setIdCar(?Car $IdCar): self
    {
        $this->IdCar = $IdCar;

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

    public function getStartLatitude(): ?float
    {
        return $this->StartLatitude;
    }

    public function setStartLatitude(float $StartLatitude): self
    {
        $this->StartLatitude = $StartLatitude;

        return $this;
    }

    public function getStartLongitude(): ?float
    {
        return $this->StartLongitude;
    }

    public function setStartLongitude(float $StartLongitude): self
    {
        $this->StartLongitude = $StartLongitude;

        return $this;
    }

    public function getEndLatitude(): ?float
    {
        return $this->EndLatitude;
    }

    public function setEndLatitude(float $EndLatitude): self
    {
        $this->EndLatitude = $EndLatitude;

        return $this;
    }

    public function getEndLongitude(): ?float
    {
        return $this->EndLongitude;
    }

    public function setEndLongitude(float $EndLongitude): self
    {
        $this->EndLongitude = $EndLongitude;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->StartTime;
    }

    public function setStartTime(\DateTimeInterface $StartTime): self
    {
        $this->StartTime = $StartTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->EndTime;
    }

    public function setEndTime(\DateTimeInterface $EndTime): self
    {
        $this->EndTime = $EndTime;

        return $this;
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

    /**
     * @return Collection<int, TravelPreference>
     */
    public function getTravelPreferences(): Collection
    {
        return $this->TravelPreferences;
    }

    public function addTravelPreference(TravelPreference $travelPreference): self
    {
        if (!$this->TravelPreferences->contains($travelPreference)) {
            $this->TravelPreferences[] = $travelPreference;
        }

        return $this;
    }

    public function removeTravelPreference(TravelPreference $travelPreference): self
    {
        $this->TravelPreferences->removeElement($travelPreference);

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

    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setTravel($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getTravel() === $this) {
                $alert->setTravel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats[] = $seat;
            $seat->setIdTravel($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getIdTravel() === $this) {
                $seat->setIdTravel(null);
            }
        }

        return $this;
    }
}
