<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PromotionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 10 ; $i++) { 
            $promotion = new Promotion;

            $promotion->setStartDate($faker->dateTimeBetween('1 week', '1 month'))
                ->setEndDate($faker->dateTimeBetween('1 month', '4 month'))
                ->setDiscountPercentage($faker->numberBetween(10,50))
                ->setCreatedAt($faker->dateTimeBetween('-3 week', '-4 days'))
                ->setUser($this->getReference('admin_' . $faker->numberBetween(1,8)));
            $manager->persist($promotion);
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
