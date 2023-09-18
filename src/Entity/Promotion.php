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

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:promotion']],
    denormalizationContext: ['groups' => ['write:promotion']],
    paginationItemsPerPage: 8
)]
#[Get(security: "is_granted('ROLE_ADMIN')")]
#[GetCollection(security: "is_granted('ROLE_ADMIN')")]
#[Post(security: "is_granted('ROLE_ADMIN')")]
#[Patch(security: "is_granted('ROLE_ADMIN')")]
#[Delete(security: "is_granted('ROLE_ADMIN')")]
#[ORM\HasLifecycleCallbacks]
class Promotion
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:promotion'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\Range(
        min: 'now',
        max: '+2 year',
        notInRangeMessage: "Choisir une date entre aujourd'hui et 2 an maximum",
    )]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\Range(
        min: 'now',
        max: '+2 year',
        notInRangeMessage: "Choisir une date entre aujourd'hui et 2 an maximum"
    )]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['read:promotion', 'write:promotion', 'read:product'])]
    #[Assert\NotBlank]
    #[Assert\Regex(
    pattern: "/^(100(\.0)?|[1-9]?\d(\.\d)?)$/",
    message: "Le format de promotion n'est pas valide. Ex: 50.5",
    )]
    private ?int $discountPercentage = null;

    #[ORM\ManyToOne(inversedBy: 'promotions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:promotion', 'write:promotion'])]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'promotions')]
    #[Groups(['read:promotion', 'write:promotion'])]
    private Collection $products;

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
}
