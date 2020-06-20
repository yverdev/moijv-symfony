<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    const NB_COMMENTS = 150;

    public function load(ObjectManager $manager)
    {
        for($i=0; $i<self::NB_COMMENTS; $i++) {
            $comment = new Comment();
            $comment->setDate((new \DateTime())->sub(date_interval_create_from_date_string($i.' days')));
            $comment->setNote(random_int(0, 10));
            $comment->setText("Mon commentaire n°$i");
            $comment->setUser($this->getReference('user'.random_int(0, UserFixtures::NB_USER - 1)));
            $comment->setProduct($this->getReference('product'.random_int(0, ProductFixtures::NB_PRODUCTS - 1)));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProductFixtures::class
        ];
    }
}