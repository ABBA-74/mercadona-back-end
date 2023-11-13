<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CommonDate
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:user', 'read:promotion', 'read:product', 'read:image', 'read:category', 'read:address'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['read:user', 'read:promotion', 'read:product', 'read:image', 'read:category', 'read:address'])]
    private ?\DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function updateCreatedAt()
    {
        if( $this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime());
        }
    }
    
    #[ORM\PreUpdate]
    public function updateUpdatedAt()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
