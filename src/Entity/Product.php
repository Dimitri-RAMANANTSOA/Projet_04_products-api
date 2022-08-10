<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
/** A Product */
#[
    ApiResource(
        normalizationContext: ['groups' => ['product.read']],
        denormalizationContext: ['groups' => ['product.write']],
        paginationItemsPerPage: 10,
        paginationMaximumItemsPerPage: 100,
        paginationClientItemsPerPage: true,
        collectionOperations: [
            'get',
            'post' => ['security' => 'is_granted("ROLE_ADMIN")']
        ],
        itemOperations: [
            'get',
            'patch' => [
                'denormalization_context' => ['groups' => ['product.patch']],
                'security' => 'is_granted("ROLE_ADMIN")'
            ],
            'delete' => ['security' => 'is_granted("ROLE_ADMIN")']
        ]
        ),
        ApiFilter(
            SearchFilter::class,
            properties: [
                'name' => 'partial',
                'description' => 'partial',
                'manufacturer.id' => 'exact',
                'manufacturer.name' => 'partial',
                'manufacturer.countryCode' => 'exact'
            ]
        ),
        ApiFilter(
            OrderFilter::class,
            properties: ['issueAt', 'name']
        )
]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product.read'])]
    /** Product ID */
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[
        Length(min: 5, max: 255),
        NotBlank(),
        Groups(['product.read','product.write','product.patch'])
    ]
    /** The MPN (Manufacturer Part Number) of the product */
    private ?string $mpn = null;

    #[ORM\Column(length: 255)]
    #[
        Length(min: 5, max: 255),
        NotBlank(),
        Groups(['product.read','product.write','product.patch','manufacturer.read'])
    ]
    /** Product Name */
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[
        Length(min: 10, max: 255),
        NotBlank(),
        Groups(['product.read','product.write','product.patch'])
    ]
    /** Product description */
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[
        NotBlank(),
        Groups(['product.read','product.write'])
    ]
    /** The date of issue of the product */
    private ?\DateTimeInterface $issueAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[
        NotBlank(),
        Groups(['product.read','product.write','product.patch'])
    ]
    /** The Manufacturer of the product */
    private ?Manufacturer $manufacturer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMpn(): ?string
    {
        return $this->mpn;
    }

    public function setMpn(?string $mpn): self
    {
        $this->mpn = $mpn;

        return $this;
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

    public function getIssueAt(): ?\DateTimeInterface
    {
        return $this->issueAt;
    }

    public function setIssueAt(\DateTimeInterface $issueAt): self
    {
        $this->issueAt = $issueAt;

        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}
