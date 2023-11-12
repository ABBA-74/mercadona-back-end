<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CommonDate;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:promotion']],
    denormalizationContext: ['groups' => ['write:promotion']],
    paginationItemsPerPage: 8,
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(
            paginationClientItemsPerPage: true,
            security: "is_granted('ROLE_ADMIN')"),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]

#[ORM\HasLifecycleCallbacks]
class Promotion
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:promotion'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
        )]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\Length(
        min: 2,
        max: 300,
        minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
        )]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\Length(
        min: 2,
        max: 300,
        minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
        )]
    private ?string $conditions = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\Range(
        min: '+1 day',
        max: '+2 year',
        notInRangeMessage: "Choisir une date entre demain et 2 an maximum",
    )]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\Range(
        min: '+1 day',
        max: '+2 year',
        notInRangeMessage: "Choisir une date entre demain et 2 an maximum"
    )]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\NotNull]
    #[Assert\Type('integer')]
    #[Assert\Range(
        min: 5,
        max: 80,
        notInRangeMessage: 'La promotion doit être comprise entre {{ min }}% et {{ max }}%',
    )]
    private ?int $discountPercentage = null;

    #[ORM\ManyToOne(inversedBy: 'promotions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:promotion'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'promotions')]
    #[Groups(['read:promotion', 'write:promotion'])]
    private Collection $products;

    // Check that endDate is after startDate
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->endDate <= $this->startDate) {
            $context->buildViolation('La date de fin doit être postérieure à la date de début.')
                    ->atPath('endDate')
                    ->addViolation();
        }
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getDiscountPercentage(): ?int
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(int $discountPercentage): static
    {
        $this->discountPercentage = $discountPercentage;

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
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addPromotion($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removePromotion($this);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): static
    {
        $this->conditions = $conditions;

        return $this;
    }
}
