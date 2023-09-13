<?php

namespace App\Tests;

use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $image = new Image();
        $date = new \DateTimeImmutable();

        $image->setLabel('test')
            ->setDescription('test')
            ->setImgFile('test.png')
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame('test', $image->getLabel());
        $this->assertSame('test', $image->getDescription());
        $this->assertSame('test.png', $image->getImgFile());
        $this->assertSame($date, $image->getCreatedAt());
        $this->assertSame($date, $image->getUpdatedAt());
    }

    public function testIsFalse(): void
    {
        $image = new Image();
        $date = new \DateTimeImmutable();

        $image->setLabel('test')
            ->setDescription('test')
            ->setImgFile('test.png')
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

            $this->assertNotSame('false', $image->getLabel());
            $this->assertNotSame('false', $image->getDescription());
            $this->assertNotSame('false.png', $image->getImgFile());
            $this->assertNotSame(null, $image->getCreatedAt());
            $this->assertNotSame(null, $image->getUpdatedAt());
    }

    public function testIsEmpty(): void
    {
        $image = new Image();

            $this->assertEmpty($image->getLabel());
            $this->assertEmpty($image->getDescription());
            $this->assertEmpty($image->getImgFile());
            $this->assertEmpty($image->getUpdatedAt());
    }
}
