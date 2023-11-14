<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use App\Entity\Traits\CommonDate;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:image']],
    denormalizationContext: ['groups' => ['write:image']],
    paginationItemsPerPage: 8,
    operations: [
        new Get(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            inputFormats: ['multipart' => ['multipart/form-data']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]

#[ORM\HasLifecycleCallbacks]
class Image
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:image', 'write:image', 'read:product', 'read:category'])]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "imgFile")]
    #[Assert\NotBlank(message: "Le fichier ne doit pas être vide")]
    #[Assert\File(
        maxSize: '2M',
        extensions: ['jpg', 'jpeg', 'png', 'webp', 'svg'],
        extensionsMessage: 'Format de fichier invalide',
        maxSizeMessage: 'Fichier trop volumineux (max: 2 Mo)'
    )]
    #[Groups(['read:image', 'write:image'])]
    private ?File $imageFile = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:image', 'write:image', 'read:product', 'write:product', 'read:product-client', 'read:category', 'write:category'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 100,
    minMessage: "Le label doit comporter au moins {{ limit }} caractères",
    maxMessage: "Le label ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $label = null;
    
    #[ORM\Column(length: 100)]
    #[Groups(['read:image', 'write:image', 'read:product', 'write:product', 'read:category', 'write:category'])]
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 2,
    max: 100,
    minMessage: "La description doit comporter au moins {{ limit }} caractères",
    maxMessage: "La description ne peut pas dépasser {{ limit }} caractères",
    )]
    private ?string $description = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:image', 'write:image', 'read:category', 'read:product', 'read:product-client'])]
    private ?string $imgFile = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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

    public function getImgFile(): ?string
    {
        return $this->imgFile;
    }

    public function setImgFile(?string $imgFile): static
    {
        $this->imgFile = $imgFile;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile)
    {
        // if ($imageFile instanceof UploadedFile) {
        //     $this->setUpdatedAt(new \DateTime());
        // }
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }
}
