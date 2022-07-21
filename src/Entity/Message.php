<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
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
    private $Text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Sent_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Read_at;

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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="MessagesSent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdSender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="MessagesReceived")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdReceiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->Sent_at;
    }

    public function setSentAt(\DateTimeInterface $Sent_at): self
    {
        $this->Sent_at = $Sent_at;

        return $this;
    }

    public function getReadAt(): ?\DateTimeInterface
    {
        return $this->Read_at;
    }

    public function setReadAt(\DateTimeInterface $Read_at): self
    {
        $this->Read_at = $Read_at;

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

    public function getIdSender(): ?User
    {
        return $this->IdSender;
    }

    public function setIdSender(?User $IdSender): self
    {
        $this->IdSender = $IdSender;

        return $this;
    }

    public function getIdReceiver(): ?User
    {
        return $this->IdReceiver;
    }

    public function setIdReceiver(?User $IdReceiver): self
    {
        $this->IdReceiver = $IdReceiver;

        return $this;
    }
}
