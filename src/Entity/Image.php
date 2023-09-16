<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use App\Entity\Traits\CommonDate;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:image']],
    denormalizationContext: ['groups' => ['write:image']]
)]
#[Get()]
#[GetCollection()]
#[Post(
    inputFormats: ['multipart' => ['multipart/form-data']]
)]
#[Delete()]
#[ORM\HasLifecycleCallbacks]
class Image
{
    use CommonDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:image'])]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "imgFile")]
    // #[Assert\NotNull]
    #[Groups(['write:image'])]
    private ?File $imageFile = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read:image', 'write:image', 'read:product', 'read:category'])]
    private ?string $label = null;
    
    #[ORM\Column(length: 100)]
    #[Groups(['read:image', 'write:image'])]
    private ?string $description = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:image', 'read:category', 'read:product', 'read:category'])]
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
        $this->imageFile = $imageFile;

        return $this;
    }
}
