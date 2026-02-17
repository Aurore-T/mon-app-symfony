<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $userAdmin = new User();
            $password = $this->hasher->hashPassword($userAdmin, 'password');

            $userAdmin->setUsername('admin' . $i)
                ->setEmail('admin' . $i . '@gmail.com')
                ->setPassword($password)
                ->setRoles(['ROLE_ADMIN']);

            $manager->persist($userAdmin);
        }

        for ($i = 1; $i <= 8; $i++) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'password');

            $user->setUsername('user' . $i)
                ->setEmail('user' . $i . '@gmail.com')
                ->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}

