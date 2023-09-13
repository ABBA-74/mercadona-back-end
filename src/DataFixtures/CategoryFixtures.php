<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use App\Data\CategoriesImagesData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use PhpParser\Node\Stmt\Foreach_;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $dataImgCategories = CategoriesImagesData::CATEGORIES;

        $categoriesLabels = array_keys($dataImgCategories);

        for($i = 1; $i <= count($categoriesLabels) ; $i++) {
            $category = new Category();

            $category->setLabel(ucfirst(str_replace('_', ' ', $categoriesLabels[$i - 1])))
                ->setCreatedAt($faker->dateTimeBetween('-8 month', '-1 week'))
                ->setImage($this->getReference('categoryImage_' . $i))
                ->setUser($this->getReference('admin_' . $faker->numberBetween(1,10)));
            $this->addReference('category_' . $i, $category);

            $manager->persist($category);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ImageFixtures::class,
        ];
    }
}
