<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_PRODUCTS = 50;

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < self::NB_PRODUCTS; $i++) {
            $product = new Product();

            $product->setName("Mon produit $i")
                ->setDescription("Ma super description $i")
                ->setPrice(random_int(100, 100000))
                ->setRef(substr(str_shuffle(md5(random_int(0, 1000000))), 0, 25))
                ->setUser($this->getReference('user' . random_int(0, UserFixtures::NB_USER - 1)))
            ;

            $nb_tags = random_int(1, 5);

            for($j = 0; $j < $nb_tags; $j++) {
                $tagIndex = random_int(0, TagFixtures::NB_TAGS - 1);
                $product->addTag($this->getReference("tag$tagIndex"));
            }

            $slugs = array_keys(CategoryFixtures::CATEGORIES);
            $slugCategory = $slugs[random_int(0, count($slugs) - 1)];
            $category = $this->getReference('category_'.$slugCategory);

            $product->setCategory($category);

            $this->addReference('product'.$i, $product);

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
            CategoryFixtures::class
        ];
    }
}