<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     * @JMS\Groups({"default"})
     */
    private $uuid;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"default"})
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @JMS\Groups({"default"})
     */
    private $event_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Groups({"default"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="events")
     * @ORM\JoinColumn(name="place_uuid", referencedColumnName="uuid", nullable=false)
     * @JMS\Groups({"default"})
     */
    private $place;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="events")
     * @ORM\JoinTable(name="users_events", joinColumns={@ORM\JoinColumn(name="event_uuid", referencedColumnName="uuid")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_uuid", referencedColumnName="uuid")})
     * @JMS\Groups({"default"})
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->event_date;
    }

    public function setEventDate(\DateTimeInterface $event_date): self
    {
        $this->event_date = $event_date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }

        return $this;
    }
}
