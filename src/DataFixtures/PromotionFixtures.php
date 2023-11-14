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

        for ($i = 1; $i <= 10 ; $i++) {
            $promotion = new Promotion;

            $promotion->setName($faker->sentence(3))
                ->setDescription($faker->sentence(24))
                ->setConditions($faker->sentence(24))
                ->setStartDate($faker->dateTimeBetween('-3 week', '1 week'))
                ->setEndDate($faker->dateTimeBetween('1 month', '4 month'))
                ->setDiscountPercentage($faker->numberBetween(10, 50))
                ->setCreatedAt($faker->dateTimeBetween('-4 week', '-3 week'))
                ->setUser($this->getReference('admin_' . $faker->numberBetween(1, 10)));
            $this->addReference('promotion_' . $i, $promotion);

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
