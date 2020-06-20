<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    const NB_TAGS = 100;

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < self::NB_TAGS; $i++) {
            $tag = new Tag();
            $tag->setName("Tag $i");
            $tag->setSlug("tag-$i");

            $this->addReference('tag'.$i, $tag);
            $manager->persist($tag);
        }

        $manager->flush();
    }
}