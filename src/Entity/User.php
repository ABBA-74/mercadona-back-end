<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CommonDate;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:user']],
    denormalizationContext: ['groups' => ['write:user']],
    paginationItemsPerPage: 8,
    operations: [
        new Get(security: "is_granted('ROLE_SUPER_ADMIN') or object == user"),
        new GetCollection(security: "is_granted('ROLE_SUPER_ADMIN')"),
        new Post(security: "is_granted('ROLE_SUPER_ADMIN')"),
        new Patch(security: "is_granted('ROLE_SUPER_ADMIN') or object == user"),
        new Delete(security: "is_granted('ROLE_SUPER_ADMIN')")
    ]
)]

#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:user'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read:user', 'write:user'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 50,
    minMessage: "Le prénom doit comporter au moins {{ limit }} caractères",
    maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read:user', 'write:user'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 50,
    minMessage: "Le nom doit comporter au moins {{ limit }} caractères",
    maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:user', 'write:user'])]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "L'e-mail doit comporter au moins {{ limit }} caractères",
        maxMessage: "L'e-mail ne peut pas dépasser {{ limit }} caractères",
        )]
    #[Assert\Email(
        message: "L'adresse e-mail n'est pas valide",
    )]
    private ?string $email = null;
    
    #[ORM\Column(length: 255)]
    #[Groups(['write:user'])]
    #[Assert\Regex(
        pattern: "/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{12,}$/",
        message: "Le mot de passe doit comporter au moins 12 caractères avec au moins une lettre majuscule, un chiffre et un caractère spécial."
    )]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(['read:user'])]
    private ?array $roles = [];

    #[ORM\Column(length: 10)]
    #[Groups(['read:user', 'write:user'])]
    #[Assert\Choice(choices: ['Mr', 'Mme'], message: 'Choisir le genre Mr ou Mme')]
    private ?string $gender = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:user', 'write:user'])]
    #[Assert\LessThan("-16 years", message: 'La personne doit avoir au moins 16 ans')]
    private ?\DateTimeImmutable $dateOfBirth = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['read:user', 'write:user'])]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
        )]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:user', 'write:user'])]
    #[Assert\Length(
        min: 5,
        max: 300,
        minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
        )]
    private ?string $internalNotes = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:user', 'write:user'])]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Promotion::class)]
    private Collection $promotions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class)]
    #[Groups(['read:user', 'write:user'])]
    private Collection $addresses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Job::class)]
    #[Groups(['read:user', 'write:user'])]
    private Collection $jobs;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->addresses = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->setUser($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            // set the owning side to null (unless already changed)
            if ($promotion->getUser() === $this) {
                $promotion->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setUser($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUser() === $this) {
                $category->setUser(null);
            }
        }

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
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    #[Groups(['read:user', 'read:category', 'read:product', 'read:promotion'])]
    public function getFullName(): string
    {
        return ucfirst($this->firstname) . ' ' . ucfirst($this->lastname);
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeImmutable $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): static
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setUser($this);
        }

        return $this;
    }

    public function removeJob(Job $job): static
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getUser() === $this) {
                $job->setUser(null);
            }
        }

        return $this;
    }
}
