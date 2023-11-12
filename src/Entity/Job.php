<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\CommonDate;
use App\Repository\JobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JobRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:job']],
    denormalizationContext: ['groups' => ['write:job']],
    paginationItemsPerPage: 8,
    operations: [
        new Get(security: "is_granted('ROLE_SUPER_ADMIN') or object == user"),
        new GetCollection(security: "is_granted('ROLE_SUPER_ADMIN')"),
        new Post(security: "is_granted('ROLE_SUPER_ADMIN')"),
        new Patch(security: "is_granted('ROLE_SUPER_ADMIN') or object == user"),
        new Delete(security: "is_granted('ROLE_SUPER_ADMIN')")
    ]
)]

class Job
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:job'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:job', 'write:job', 'read:user', 'write:user'])]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "Le champ doit comporter au moins {{ limit }} caractères",
        maxMessage: "Le champ ne peut pas dépasser {{ limit }} caractères",
        )]
    private ?string $jobTitle = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['read:job', 'write:job', 'read:user', 'write:user'])]
    #[Assert\Choice(
        choices: ['Cadre', 'Ingénieur', 'Administratif', 'Commercial', 'Opérationnel'],
        message: "La categorie d'emploi est invalide"
        )
    ]
    private ?string $jobCategory = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:job', 'write:job', 'read:user', 'write:user'])]
    #[Assert\Range(
        min: 1,
        max: 10,
        notInRangeMessage: "L'échelon doit être compris entre {{ min }} et {{ max }}",
    )]
    private ?int $jobLevel = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:job', 'write:job', 'read:user', 'write:user'])]
    #[Assert\Range(
        min: 100,
        max: 300,
        notInRangeMessage: "Le coefficient doit être compris entre {{ min }} et {{ max }}",
    )]
    private ?int $coefficient = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['read:job', 'write:job', 'read:user', 'write:user'])]
    #[Assert\Choice(
        choices: ['Temps plein', 'Temps partiel', 'Contractuel', 'Stagiaire', 'Temporaire'],
        message: "Le type d'emploi est invalide"
        )
    ]
    private ?string $employmentType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['read:job', 'write:job'])]
    #[Assert\LessThanOrEqual('today', message: "La date d'embauche doit être aujourd'hui ou dans le passé")]
    private ?\DateTimeInterface $hireDate = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getJobCategory(): ?string
    {
        return $this->jobCategory;
    }

    public function setJobCategory(?string $jobCategory): static
    {
        $this->jobCategory = $jobCategory;

        return $this;
    }

    public function getJobLevel(): ?int
    {
        return $this->jobLevel;
    }

    public function setJobLevel(?int $jobLevel): static
    {
        $this->jobLevel = $jobLevel;

        return $this;
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(?int $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getEmploymentType(): ?string
    {
        return $this->employmentType;
    }

    public function setEmploymentType(?string $employmentType): static
    {
        $this->employmentType = $employmentType;

        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hireDate;
    }

    public function setHireDate(?\DateTimeInterface $hireDate): static
    {
        $this->hireDate = $hireDate;

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
