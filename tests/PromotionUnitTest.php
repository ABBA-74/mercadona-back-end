<?php

namespace App\Tests;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PromotionUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $promotion = new Promotion();
        $date = new \DateTimeImmutable();

        $promotion->setStartDate($date)
            ->setEndDate($date)
            ->setDiscountPercentage(33)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame($date, $promotion->getStartDate());
        $this->assertSame($date, $promotion->getEndDate());
        $this->assertSame(33, $promotion->getDiscountPercentage());
        $this->assertSame($date, $promotion->getCreatedAt());
        $this->assertSame($date, $promotion->getUpdatedAt());
    }

    public function testIsFalse(): void
    {
        $promotion = new Promotion();
        $date = new \DateTimeImmutable();

        $promotion->setStartDate($date)
        ->setEndDate($date)
        ->setDiscountPercentage(33)
        ->setCreatedAt($date)
        ->setUpdatedAt($date);

        $this->assertNotSame(null, $promotion->getStartDate());
        $this->assertNotSame(null, $promotion->getEndDate());
        $this->assertNotSame(66, $promotion->getDiscountPercentage());
        $this->assertNotSame(null, $promotion->getCreatedAt());
        $this->assertNotSame(null, $promotion->getUpdatedAt());
    }

    public function testIsEmpty(): void
    {
        $promotion = new Promotion();

            $this->assertEmpty($promotion->getStartDate());
            $this->assertEmpty($promotion->getEndDate());
            $this->assertEmpty($promotion->getDiscountPercentage());
            $this->assertEmpty($promotion->getUpdatedAt());
    }

    public function testGetSetUser(): void
    {
        $promotion = new Promotion();
        $user = new User();

        $this->assertEmpty($promotion->getUser());

        $promotion->setUser($user);
        $this->assertSame($user, $promotion->getUser());
        $this->assertNotSame(null, $promotion->getUser());
    }

    public function testAddGetRemoveProduct(): void
    {
        $promotion = new Promotion();
        $product = new Product();

        $this->assertEmpty($promotion->getProducts());

        $promotion->addProduct($product);
        $this->assertContains($product, $promotion->getProducts());

        $promotion->removeProduct($product);
        $this->assertEmpty($promotion->getProducts());
    }
}
