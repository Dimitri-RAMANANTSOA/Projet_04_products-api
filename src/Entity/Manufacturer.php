<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ManufacturerRepository;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: ManufacturerRepository::class)]
/** A Manufacturer */
#[ApiResource]
class Manufacturer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** Manufacturer ID */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /** Manufacturer Name */
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    /** Manufacturer Description */
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    /** Manufacturer CountryCode */
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    /** Manufacturer Listed Date */
    private ?\DateTimeInterface $listedAt = null;

    public function __construct()
    {
        $this->listedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getListedAt(): ?\DateTimeInterface
    {
        return $this->listedAt;
    }

    public function setListedAt(\DateTimeInterface $listedAt): self
    {
        $this->listedAt = $listedAt;

        return $this;
    }
}
