<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Data\CategoriesImagesData;
use App\Data\ProductsImagesData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $dataImgProducts = ProductsImagesData::PRODUCTS;
        $dataImgCategories = CategoriesImagesData::CATEGORIES;

        /**
         * Create list of image for product
         */
        $i = 1;
        foreach ($dataImgProducts as $productName => $productImage) {
            $image = new Image();
            
            $image->setLabel(ucfirst(str_replace("_", " ", $productName)))
                ->setDescription($faker->sentence())
                ->setImgFile($productImage)
                ->setCreatedAt($faker->dateTimeBetween('-8 month', '-1 week'));
            $this->addReference('productImage_' . $i, $image);
            $i++;

            $manager->persist($image);
        }

        /**
         * Create list of image for category
         */
        $j = 1;
        foreach ($dataImgCategories as $categoryName => $categoryImage) {
            $image = new Image();
            
            $image->setLabel(ucfirst(str_replace("_", " ", $categoryName)))
                ->setDescription($faker->sentence())
                ->setImgFile($categoryImage)
                ->setCreatedAt($faker->dateTimeBetween('-8 month', '-1 week'));
            $this->addReference('categoryImage_' . $j, $image);
            $j++;
            
            $manager->persist($image);
        }

        $manager->flush();
    }
}
