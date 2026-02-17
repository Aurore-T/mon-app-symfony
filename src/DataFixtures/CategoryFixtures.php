<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['T-shirt', 'Pantalon', 'Chaussure', 'Chaussettes', 'Sous-vêtement', 'Accessoire'];

        foreach ($categories as $key => $value) {
            $category = new Category();

            $category->setTitle ($value);

            $manager->persist($category);

            $this->addReference('category-' . $key, $category);

        }

        $manager->flush();

    }
}
