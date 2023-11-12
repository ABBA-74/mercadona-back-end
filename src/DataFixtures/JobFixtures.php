<?php

namespace App\DataFixtures;

use App\Data\JobsData;
use App\Entity\Job;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $jobsData = JobsData::JOBS;
        $jobTitlesData = array_keys($jobsData);
        $employmentTypeData = JobsData::EMPLOYMENT_TYPE;

        for ($i = 1; $i <= 10 ; $i++) {
            $job = new Job;

            $id = $faker->numberBetween(0, count($jobsData)-1);

            $job->setJobTitle($faker->randomElement($jobsData[$jobTitlesData[$id]]))
                ->setJobCategory($jobTitlesData[$id])
                ->setEmploymentType($faker->randomElement($employmentTypeData))
                ->setJobLevel($faker->numberBetween(1, 10))
                ->setCoefficient($faker->numberBetween(20, 60) * 5 )
                ->setHireDate($faker->dateTimeBetween('-3 year', '-4 days'))
                ->setCreatedAt($faker->dateTimeBetween('-3 year', '-4 days'))
                ->setUser($this->getReference('admin_' . $i));

            $manager->persist($job);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
