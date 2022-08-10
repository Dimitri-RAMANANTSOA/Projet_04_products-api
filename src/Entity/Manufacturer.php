<?php

namespace App\Entity;

use App\Entity\Product;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ManufacturerRepository::class)]
/** A Manufacturer */
#[ApiResource(
    normalizationContext: ['groups' => ['manufacturer.read']],
        denormalizationContext: ['groups' => ['manufacturer.write']],
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage: 100,
    paginationClientItemsPerPage: true,
    itemOperations: [
        'get',
        'patch' => [
            'denormalization_context' => ['groups' => ['manufacturer.patch']]
        ],
        'delete'
    ]
)]
class Manufacturer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** Manufacturer ID */
    #[Groups(['manufacturer.read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[
        Length(min: 5),
        NotBlank(),
        Groups(['product.read','manufacturer.read','manufacturer.write', 'manufacturer.patch'])
    ]
    /** Manufacturer Name */
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[
        Length(min: 10),
        NotBlank(),
        Groups(['manufacturer.read','manufacturer.write','manufacturer.patch'])
    ]
    /** Manufacturer Description */
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[
        Length(min: 5),
        NotBlank(),
        Country(),
        Groups(['manufacturer.read','manufacturer.write','manufacturer.patch'])
    ]
    /** Manufacturer CountryCode */
    private ?string $countryCode = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[
        NotNull(),
        Groups(['manufacturer.read','manufacturer.write'])
    ]
    /** Manufacturer Listed Date */
    private ?\DateTimeInterface $listedAt = null;

    #[ORM\OneToMany(mappedBy: 'manufacturer', targetEntity: Product::class, cascade: ['persist', 'remove'])]
    #[ApiSubresource]
    #[Groups(['manufacturer.read','manufacturer.write','manufacturer.patch'])]
    private Collection $products;

    public function __construct()
    {
        $this->listedAt = new \DateTime();
        $this->products = new ArrayCollection();
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

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setManufacturer($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getManufacturer() === $this) {
                $product->setManufacturer(null);
            }
        }

        return $this;
    }
}
