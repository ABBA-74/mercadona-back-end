<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ProductUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $product = new Product();
        $date = new \DateTimeImmutable();

        $product->setLabel('test')
            ->setDescription('test')
            ->setOriginalPrice(3.33)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame('test', $product->getLabel());
        $this->assertSame('test', $product->getDescription());
        $this->assertSame('3.33', $product->getOriginalPrice());
        $this->assertSame($date, $product->getCreatedAt());
        $this->assertSame($date, $product->getUpdatedAt());
    }

    public function testIsFalse(): void
    {
        $product = new Product();
        $date = new \DateTimeImmutable();

        $product->setLabel('test')
            ->setDescription('test')
            ->setOriginalPrice(3.33)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

            $this->assertNotSame('false', $product->getLabel());
            $this->assertNotSame('false', $product->getDescription());
            $this->assertNotSame('6.66', $product->getOriginalPrice());
            $this->assertNotSame(null, $product->getCreatedAt());
            $this->assertNotSame(null, $product->getUpdatedAt());
    }

    public function testIsEmpty(): void
    {
        $product = new Product();

            $this->assertEmpty($product->getLabel());
            $this->assertEmpty($product->getDescription());
            $this->assertEmpty($product->getOriginalPrice());
            $this->assertEmpty($product->getUpdatedAt());
    }

    public function testGetSetImage(): void
    {
        $product = new Product();
        $image = new Image();

        $this->assertEmpty($product->getImage());

        $product->setImage($image);
        $this->assertSame($image, $product->getImage());
        $this->assertNotSame(null, $product->getImage());
    }

    public function testGetSetUser(): void
    {
        $product = new Product();
        $user = new User();

        $this->assertEmpty($product->getUser());

        $product->setUser($user);
        $this->assertSame($user, $product->getUser());
        $this->assertNotSame(null, $product->getUser());
    }

    public function testGetSetCategory(): void
    {
        $product = new Product();
        $category = new Category();

        $this->assertEmpty($product->getCategory());

        $product->setCategory($category);
        $this->assertSame($category, $product->getCategory());
        $this->assertNotSame(null, $product->getCategory());
    }

    public function testAddGetRemovePromotion(): void
    {
        $product = new Product();
        $promotion = new Promotion();

        $this->assertEmpty($product->getPromotions());

        $product->addPromotion($promotion);
        $this->assertContains($promotion, $product->getPromotions());

        $product->removePromotion($promotion);
        $this->assertEmpty($product->getPromotions());
    }
}
