<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
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
    paginationItemsPerPage: 8
)]
#[Get()]
#[GetCollection()]
#[Post(security: "is_granted('ROLE_ADMIN')")]
#[Patch(security: "is_granted('ROLE_ADMIN')")]
#[Delete(security: "is_granted('ROLE_ADMIN')")]
#[ApiFilter(SearchFilter::class, properties: ['label' => 'partial', 'category' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:product'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:product', 'write:product', 'read:promotion'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 100,
    minMessage: "Le label doit comporter au moins {{ limit }} caractères",
    maxMessage: "Le label ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:product', 'write:product'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 200,
    minMessage: "La description doit comporter au moins {{ limit }} caractères",
    maxMessage: "La description ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read:product', 'write:product'])]
    #[Assert\NotBlank]
    private ?float $originalPrice = null;
    
    #[ORM\Column(nullable: true)]
    #[Groups(['read:product'])]
    private ?float $discountedPrice = null;
    
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['read:product'])]
    private ?int $currentPromotionPercentage = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[Groups(['read:product', 'write:product'])]
    private ?Image $image = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:product', 'write:product'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:product'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Promotion::class, inversedBy: 'products')]
    #[Groups(['read:product'])]
    private Collection $promotions;

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
}
