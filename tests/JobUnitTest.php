<?php

namespace App\Tests;

use App\Entity\Job;
use App\Entity\Product;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class JobUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $job = new Job();
        $date = new \DateTimeImmutable();

        $job->setJobTitle('test')
            ->setJobCategory('test')
            ->setEmploymentType('test')
            ->setJobLevel(10)
            ->setCoefficient(300)
            ->setHireDate($date)
            ->setCreatedAt($date)
            ->setUpdatedAt($date);

        $this->assertSame('test', $job->getJobTitle());
        $this->assertSame('test', $job->getJobCategory());
        $this->assertSame('test', $job->getEmploymentType());
        $this->assertSame(10, $job->getJobLevel());
        $this->assertSame(300, $job->getCoefficient());
        $this->assertSame($date, $job->getHireDate());
        $this->assertSame($date, $job->getCreatedAt());
        $this->assertSame($date, $job->getUpdatedAt());
    }

    public function testIsFalse(): void
    {
        $job = new Job();
        $date = new \DateTimeImmutable();

        $job->setJobTitle('test')
        ->setJobCategory('test')
        ->setEmploymentType('test')
        ->setJobLevel(10)
        ->setCoefficient(300)
        ->setHireDate($date)
        ->setCreatedAt($date)
        ->setUpdatedAt($date);

        $this->assertNotSame('false', $job->getJobTitle());
        $this->assertNotSame('false', $job->getJobCategory());
        $this->assertNotSame('false', $job->getEmploymentType());
        $this->assertNotSame(20, $job->getJobLevel());
        $this->assertNotSame(20, $job->getCoefficient());
        $this->assertNotSame(null, $job->getHireDate());
        $this->assertNotSame(null, $job->getCreatedAt());
        $this->assertNotSame(null, $job->getUpdatedAt());
    }

    public function testIsEmpty(): void
    {
        $job = new Job();

            $this->assertEmpty($job->getJobTitle());
            $this->assertEmpty($job->getJobCategory());
            $this->assertEmpty($job->getEmploymentType());
            $this->assertEmpty($job->getJobLevel());
            $this->assertEmpty($job->getCoefficient());
            $this->assertEmpty($job->getHireDate());
            $this->assertEmpty($job->getUpdatedAt());
    }

    public function testGetSetUser(): void
    {
        $job = new Job();
        $user = new User();

        $this->assertEmpty($job->getUser());

        $job->setUser($user);
        $this->assertSame($user, $job->getUser());
        $this->assertNotSame(null, $job->getUser());
    }
}
