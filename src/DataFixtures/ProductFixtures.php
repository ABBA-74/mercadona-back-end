<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Data\ProductsDetailsData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $dataDetailsProducts = ProductsDetailsData::PRODUCT_DETAILS;

        $i = $j = 1;
        foreach ($dataDetailsProducts as $productLabel => $productDescription) {
            $product = new Product();

            $product->setLabel($productLabel)
                ->setDescription($productDescription)
                ->setInternalNotes($faker->sentence(18))
                ->setOriginalPrice($faker->randomFloat(2, 3, 8))
                ->setCreatedAt($faker->dateTimeBetween('-5 month', '-1 month'))
                ->setImage($this->getReference('productImage_' . $i))
                ->setUser($this->getReference('admin_' . $faker->numberBetween(1, 10)))
                ->setCategory($this->getReference('category_' . $j))
                ->setIsActive($faker->boolean());

            if (!($i % 4)) {
                $j++;
            }
            if ($faker->boolean(70)) {
                $product->addPromotion($this->getReference('promotion_' . $faker->numberBetween(1,10)));
            }
            $i++;

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ImageFixtures::class,
            CategoryFixtures::class,
            PromotionFixtures::class,
            UserFixtures::class,
        ];
    }
}
