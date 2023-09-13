<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Promotion;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();
        $date = new \DateTimeImmutable();

        $user->setFirstname('firstname')
            ->setLastname('lastname')
            ->setEmail('test@test.com')
            ->setPassword('password')
            ->setRoles(['ROLE_TEST'])
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame($user->getFirstname(),'firstname');
        $this->assertSame($user->getLastname(), 'lastname');
        $this->assertSame($user->getEmail(), 'test@test.com');
        $this->assertSame($user->getPassword(), 'password');
        $this->assertSame($user->getRoles(), ['ROLE_TEST', 'ROLE_USER']);
        $this->assertSame($user->getCreatedAt(), $date);
        $this->assertSame($user->getUpdatedAt(), $date);
    }

    public function testIsFalse(): void
    {
        $user = new User();
        $date = new \DateTimeImmutable();

        $user->setFirstname('firstname')
            ->setLastname('lastname')
            ->setEmail('test@test.com')
            ->setPassword('password')
            ->setRoles(['ROLE_TEST'])
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

            $this->assertNotSame($user->getFirstname(), 'false');
            $this->assertNotSame($user->getLastname(), 'false');
            $this->assertNotSame($user->getEmail(), 'false@false.com');
            $this->assertNotSame($user->getPassword(), 'false');
            $this->assertNotSame($user->getRoles(), ['ROLE_FALSE']);
            $this->assertNotSame($user->getCreatedAt(), null);
            $this->assertNotSame($user->getUpdatedAt(), null);
    }

    public function testIsEmpty(): void
    {
        $user = new User();

            $this->assertEmpty($user->getFirstname());
            $this->assertEmpty($user->getLastname());
            $this->assertEmpty($user->getEmail());
            $this->assertEmpty($user->getPassword());
            $this->assertEmpty($user->getUpdatedAt());
    }

    public function testAddGetRemoveCategory(): void
    {
        $user = new User();
        $category = new Category();

        $this->assertEmpty($user->getCategories());

        $user->addCategory($category);
        $this->assertContains($category, $user->getCategories());

        $user->removeCategory($category);
        $this->assertEmpty($user->getCategories());
    }

    public function testAddGetRemoveProduct(): void
    {
        $user = new User();
        $product = new Product();

        $this->assertEmpty($user->getProducts());

        $user->addProduct($product);
        $this->assertContains($product, $user->getProducts());

        $user->removeProduct($product);
        $this->assertEmpty($user->getProducts());
    }

    public function testAddGetRemovePromotion(): void
    {
        $user = new User();
        $promotion = new Promotion();

        $this->assertEmpty($user->getPromotions());

        $user->addPromotion($promotion);
        $this->assertContains($promotion, $user->getPromotions());

        $user->removePromotion($promotion);
        $this->assertEmpty($user->getPromotions());
    }
}
