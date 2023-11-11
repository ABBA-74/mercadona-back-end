<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $category = new Category();
        $date = new \DateTimeImmutable();

        $category->setLabel('test')
            ->setIsActive(true)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame('test', $category->getLabel());
        $this->assertSame(true, $category->getIsActive());
        $this->assertSame($date, $category->getCreatedAt());
        $this->assertSame($date, $category->getUpdatedAt());
    }

    public function testIsFalse(): void
    {
        $category = new Category();
        $date = new \DateTimeImmutable();

        $category->setLabel('test')
            ->setIsActive(true)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

            $this->assertNotSame('false', $category->getLabel());
            $this->assertNotSame(false, $category->getIsActive());
            $this->assertNotSame(null, $category->getCreatedAt());
            $this->assertNotSame(null, $category->getUpdatedAt());
    }

    public function testIsEmpty(): void
    {
        $category = new Category();

            $this->assertEmpty($category->getLabel());
            $this->assertEmpty($category->getUpdatedAt());
    }

    public function testGetSetImage(): void
    {
        $category = new Category();
        $image = new Image();

        $this->assertEmpty($category->getImage());

        $category->setImage($image);
        $this->assertSame($image, $category->getImage());
        $this->assertNotSame(null, $category->getImage());
    }

    public function testGetSetUser(): void
    {
        $category = new Category();
        $user = new User();

        $this->assertEmpty($category->getUser());

        $category->setUser($user);
        $this->assertSame($user, $category->getUser());
        $this->assertNotSame(null, $category->getUser());
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

    public function testAddGetRemoveProduct(): void
    {
        $category = new Category();
        $product = new Product();

        $this->assertEmpty($category->getProducts());

        $category->addProduct($product);
        $this->assertContains($product, $category->getProducts());

        $category->removeProduct($product);
        $this->assertEmpty($category->getProducts());
    }
}
