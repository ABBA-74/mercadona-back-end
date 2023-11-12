<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private SluggerInterface $slugger
    ) { }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            $user->setGender($faker->randomElement(['Mr', 'Mme']))
                ->setFirstname($faker->unique()->firstname())
                ->setLastname($faker->unique()->lastName())
                ->setDateOfBirth(
                    \DateTimeImmutable::createFromMutable(
                        $faker->dateTimeBetween('-65 year', '-20 year')
                        )
                )
                ->setPhone('06 ' . $faker->bothify('## ## ## ##'))
                ->setInternalNotes($faker->sentence(28))
                ->setIsActive($faker->boolean())
                ->setCreatedAt($faker->dateTimeBetween('-6 month', '-5 month'))
                ->setEmail(
                    sprintf('%s.%s@%s',
                    $this->slugger->slug($user->getFirstname())->lower(),
                    $this->slugger->slug($user->getLastname())->lower(),
                    $faker->safeEmailDomain()
                    )
                );
            
            if ($i < 3) {
                $user->setPassword($this->hasher->hashPassword($user, 'super-admin'))
                    ->setRoles(['ROLE_SUPER_ADMIN']);
            } else {
                $user->setPassword($this->hasher->hashPassword($user, 'admin'))
                    ->setRoles(['ROLE_ADMIN']);
            }
            $this->addReference('admin_' . $i, $user);
            
            $manager->persist($user);
        }
        $manager->flush();
    }
}
