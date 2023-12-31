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
            ->setGender('Mr')
            ->setPhone('1234567890')
            ->setInternalNotes(('internal notes'))
            ->setIsActive(true)
            ->setDateOfBirth($date)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame('firstname', $user->getFirstname());
        $this->assertSame('lastname', $user->getLastname(), );
        $this->assertSame('test@test.com', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame(['ROLE_TEST', 'ROLE_USER'], $user->getRoles());
        $this->assertSame('Mr', $user->getGender());
        $this->assertSame('1234567890', $user->getPhone());
        $this->assertSame('internal notes', $user->getInternalNotes());
        $this->assertSame(true, $user->getIsActive());
        $this->assertSame($date, $user->getDateOfBirth());
        $this->assertSame($date, $user->getCreatedAt());
        $this->assertSame($date, $user->getUpdatedAt());
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
            ->setGender('Mr')
            ->setPhone('1234567890')
            ->setInternalNotes(('internal notes'))
            ->setIsActive(true)
            ->setDateOfBirth($date)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

            $this->assertNotSame('false', $user->getFirstname());
            $this->assertNotSame('false', $user->getLastname());
            $this->assertNotSame('false@false.com', $user->getEmail());
            $this->assertNotSame('false', $user->getPassword());
            $this->assertNotSame(['ROLE_FALSE'], $user->getRoles());
            $this->assertNotSame('false', $user->getGender());
            $this->assertNotSame('0987654321', $user->getPhone());
            $this->assertNotSame('false', $user->getInternalNotes());
            $this->assertNotSame(false, $user->getIsActive());
            $this->assertNotSame(null, $user->getDateOfBirth());
            $this->assertNotSame(null, $user->getCreatedAt());
            $this->assertNotSame(null, $user->getUpdatedAt());
    }

    public function testIsEmpty(): void
    {
        $user = new User();

            $this->assertEmpty($user->getFirstname());
            $this->assertEmpty($user->getLastname());
            $this->assertEmpty($user->getEmail());
            $this->assertEmpty($user->getPassword());
            $this->assertEmpty($user->getGender());
            $this->assertEmpty($user->getPhone());
            $this->assertEmpty($user->getInternalNotes());
            $this->assertEmpty($user->getIsActive());
            $this->assertEmpty($user->getDateOfBirth());
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
