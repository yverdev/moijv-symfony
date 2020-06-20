<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'jeux' => 'Jeux',
        'console' => 'Console',
        'cles-steam' => 'Clés steam'
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $slug => $name) {
            $category = new Category();
            $category->setName($name);
            $category->setSlug($slug);
            $this->addReference('category_'.$slug, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}