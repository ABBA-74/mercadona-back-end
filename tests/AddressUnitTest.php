<?php

namespace App\Tests;

use App\Entity\Address;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AddressUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $address = new Address();
        $date = new \DateTimeImmutable();

        $address->setAddress('address')
            ->setCity('city')
            ->setPostalCode('12345')
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame('address', $address->getAddress());
        $this->assertSame('city', $address->getCity(), );
        $this->assertSame('12345', $address->getPostalCode());
        $this->assertSame($date, $address->getCreatedAt());
        $this->assertSame($date, $address->getUpdatedAt());
    }

    public function testIsFalse(): void
    {
        $address = new Address();
        $date = new \DateTimeImmutable();

        $address->setAddress('address')
            ->setCity('city')
            ->setPostalCode('12345')
            ->setCreatedAt($date)
            ->setUpdatedAt($date);


            $this->assertNotSame('false', $address->getAddress());
            $this->assertNotSame('false', $address->getCity());
            $this->assertNotSame('00000', $address->getPostalCode());
            $this->assertNotSame(null, $address->getCreatedAt());
            $this->assertNotSame(null, $address->getUpdatedAt());
    }

    public function testIsEmpty(): void
    {
        $address = new Address();

            $this->assertEmpty($address->getAddress());
            $this->assertEmpty($address->getCity());
            $this->assertEmpty($address->getPostalCode());
            $this->assertEmpty($address->getUpdatedAt());
    }

    public function testGetSetUser(): void
    {
        $address = new Address();
        $user = new User();
    
        $this->assertEmpty($address->getUser());
    
        $address->setUser($user);
        $this->assertSame($user, $address->getUser());
        $this->assertNotSame(null, $address->getUser());
    }
}