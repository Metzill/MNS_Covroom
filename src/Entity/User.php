<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Firstname;

    /**
     * @ORM\Column(type="integer")
     */
    private $Age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ProfilePicture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $MailConfirmation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Role;

    /**
     * @ORM\OneToMany(targetEntity=Car::class, mappedBy="IdUser", orphanRemoval=true)
     */
    private $cars;

    /**
     * @ORM\OneToMany(targetEntity=Travel::class, mappedBy="IdUser")
     */
    private $Travels;

    /**
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="IdUser", orphanRemoval=true)
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="IdUserRating", orphanRemoval=true)
     */
    private $RatingWritten;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="IdUserRated", orphanRemoval=true)
     */
    private $RatingReceived;

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
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="IdSender", orphanRemoval=true)
     */
    private $MessagesSent;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="IdReceiver", orphanRemoval=true)
     */
    private $MessagesReceived;

    /**
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="IdUser", orphanRemoval=true)
     */
    private $alerts;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="IdUser", orphanRemoval=true)
     */
    private $bookings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PhoneNumber;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->Travels = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->RatingWritten = new ArrayCollection();
        $this->RatingReceived = new ArrayCollection();
        $this->MessagesSent = new ArrayCollection();
        $this->MessagesReceived = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): self
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): self
    {
        $this->Firstname = $Firstname;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->Age;
    }

    public function setAge(int $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->ProfilePicture;
    }

    public function setProfilePicture(string $ProfilePicture): self
    {
        $this->ProfilePicture = $ProfilePicture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function isMailConfirmation(): ?bool
    {
        return $this->MailConfirmation;
    }

    public function setMailConfirmation(bool $MailConfirmation): self
    {
        $this->MailConfirmation = $MailConfirmation;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->setIdUser($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getIdUser() === $this) {
                $car->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getTravels(): Collection
    {
        return $this->Travels;
    }

    public function addTravel(Travel $travel): self
    {
        if (!$this->Travels->contains($travel)) {
            $this->Travels[] = $travel;
            $travel->setIdUser($this);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): self
    {
        if ($this->Travels->removeElement($travel)) {
            // set the owning side to null (unless already changed)
            if ($travel->getIdUser() === $this) {
                $travel->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setIdUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getIdUser() === $this) {
                $favorite->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRatingWritten(): Collection
    {
        return $this->RatingWritten;
    }

    public function addRatingWritten(Rate $ratingWritten): self
    {
        if (!$this->RatingWritten->contains($ratingWritten)) {
            $this->RatingWritten[] = $ratingWritten;
            $ratingWritten->setIdUserRating($this);
        }

        return $this;
    }

    public function removeRatingWritten(Rate $ratingWritten): self
    {
        if ($this->RatingWritten->removeElement($ratingWritten)) {
            // set the owning side to null (unless already changed)
            if ($ratingWritten->getIdUserRating() === $this) {
                $ratingWritten->setIdUserRating(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRatingReceived(): Collection
    {
        return $this->RatingReceived;
    }

    public function addRatingReceived(Rate $ratingReceived): self
    {
        if (!$this->RatingReceived->contains($ratingReceived)) {
            $this->RatingReceived[] = $ratingReceived;
            $ratingReceived->setIdUserRated($this);
        }

        return $this;
    }

    public function removeRatingReceived(Rate $ratingReceived): self
    {
        if ($this->RatingReceived->removeElement($ratingReceived)) {
            // set the owning side to null (unless already changed)
            if ($ratingReceived->getIdUserRated() === $this) {
                $ratingReceived->setIdUserRated(null);
            }
        }

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
     * @return Collection<int, Message>
     */
    public function getMessagesSent(): Collection
    {
        return $this->MessagesSent;
    }

    public function addMessagesSent(Message $messagesSent): self
    {
        if (!$this->MessagesSent->contains($messagesSent)) {
            $this->MessagesSent[] = $messagesSent;
            $messagesSent->setIdSender($this);
        }

        return $this;
    }

    public function removeMessagesSent(Message $messagesSent): self
    {
        if ($this->MessagesSent->removeElement($messagesSent)) {
            // set the owning side to null (unless already changed)
            if ($messagesSent->getIdSender() === $this) {
                $messagesSent->setIdSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesReceived(): Collection
    {
        return $this->MessagesReceived;
    }

    public function addMessagesReceived(Message $messagesReceived): self
    {
        if (!$this->MessagesReceived->contains($messagesReceived)) {
            $this->MessagesReceived[] = $messagesReceived;
            $messagesReceived->setIdReceiver($this);
        }

        return $this;
    }

    public function removeMessagesReceived(Message $messagesReceived): self
    {
        if ($this->MessagesReceived->removeElement($messagesReceived)) {
            // set the owning side to null (unless already changed)
            if ($messagesReceived->getIdReceiver() === $this) {
                $messagesReceived->setIdReceiver(null);
            }
        }

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
            $alert->setIdUser($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getIdUser() === $this) {
                $alert->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setIdUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getIdUser() === $this) {
                $booking->setIdUser(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber(string $PhoneNumber): self
    {
        $this->PhoneNumber = $PhoneNumber;

        return $this;
    }
}
