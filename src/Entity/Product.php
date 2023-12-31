<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CommonDate;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:product']],
    denormalizationContext: ['groups' => ['write:product']],
    paginationItemsPerPage: 8,
    operations: [
        new GetCollection(
            uriTemplate: '/products/active',
            normalizationContext: ['groups' => ['read:product-client']],
        ),
        new Get(
            uriTemplate: '/products/active/{id}',
            normalizationContext: ['groups' => ['read:product-client']],
        ),
        new GetCollection(
            paginationClientItemsPerPage: true,
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]

#[ApiFilter(
    SearchFilter::class,
    properties: ['label' => 'partial', 'category' => 'exact']
)]
#[ApiFilter(
    ExistsFilter::class,
    properties: ['discountedPrice']
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['id', 'currentPromotionPercentage', 'label', 'createdAt'],
    arguments: ['orderParameterName' => 'order']
)]
#[ApiFilter(
    BooleanFilter::class,
    properties: ['isActive'=> ['security' => "is_granted('ROLE_ADMIN')"]]
)]

#[ORM\HasLifecycleCallbacks]
class Product
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:product', 'read:product-client'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:product', 'read:product-client', 'write:product', 'read:promotion'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 100,
    minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
    maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:product', 'read:product-client', 'write:product'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 200,
    minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
    maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read:product', 'read:product-client', 'write:product'])]
    #[Assert\NotBlank]
    private ?float $originalPrice = null;
    
    #[ORM\Column(nullable: true)]
    #[Groups(['read:product', 'read:product-client'])]
    private ?float $discountedPrice = null;
    
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['read:product', 'read:product-client'])]
    private ?int $currentPromotionPercentage = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[Groups(['read:product', 'read:product-client', 'write:product'])]
    private ?Image $image = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:product', 'read:product-client', 'write:product'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:product'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Promotion::class, inversedBy: 'products')]
    #[Groups(['read:product'])]
    private Collection $promotions;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:product', 'write:product'])]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 300,
        minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
        )]
    #[Groups(['read:product', 'write:product'])]
    private ?string $internalNotes = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOriginalPrice(): ?float
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(float $originalPrice): static
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    #[Groups(['read:product'])]
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        $this->promotions->removeElement($promotion);

        return $this;
    }

    public function getDiscountedPrice(): ?float
    {
        return $this->discountedPrice;
    }

    public function setDiscountedPrice(?float $discountedPrice): static
    {
        $this->discountedPrice = ($discountedPrice !== null) ? round($discountedPrice, 2) : null;
        return $this;
    }

    public function getCurrentPromotionPercentage(): ?int
    {
        return $this->currentPromotionPercentage;
    }

    public function setCurrentPromotionPercentage(?int $currentPromotionPercentage): static
    {
        $this->currentPromotionPercentage = $currentPromotionPercentage;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getInternalNotes(): ?string
    {
        return $this->internalNotes;
    }

    public function setInternalNotes(?string $internalNotes): static
    {
        $this->internalNotes = $internalNotes;

        return $this;
    }
}
