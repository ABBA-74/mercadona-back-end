<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CommonDate;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:address']],
    denormalizationContext: ['groups' => ['write:address']],
    paginationItemsPerPage: 8,
    operations: [
        new Get(security: "is_granted('ROLE_SUPER_ADMIN') or object == user"),
        new GetCollection(security: "is_granted('ROLE_SUPER_ADMIN')"),
        new Post(security: "is_granted('ROLE_SUPER_ADMIN')"),
        new Patch(security: "is_granted('ROLE_SUPER_ADMIN') or object == user"),
        new Delete(security: "is_granted('ROLE_SUPER_ADMIN')")
    ]
)]
class Address
{
    use CommonDate;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:address'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "L'adresse nom doit comporter au moins {{ limit }} caractères",
        maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères",
        )]
    #[Groups(['read:address', 'write:address', 'read:user', 'write:user'])]
    private ?string $address = null;

    #[ORM\Column(length: 10)]
    #[Assert\Length(
        min: 4,
        max: 10,
        minMessage: "Le code postal doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le code postal ne peut pas dépasser {{ limit }} caractères",
    )]
    #[Groups(['read:address', 'write:address', 'read:user', 'write:user'])]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "La ville doit comporter au moins {{ limit }} caractères",
        maxMessage: "La ville ne peut pas dépasser {{ limit }} caractères",
    )]
    #[Groups(['read:address', 'write:address', 'read:user', 'write:user'])]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[Groups(['read:address'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

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
}
