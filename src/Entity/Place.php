<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $uuid;

    /**
     * @JMS\Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @JMS\Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @JMS\Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @JMS\Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
