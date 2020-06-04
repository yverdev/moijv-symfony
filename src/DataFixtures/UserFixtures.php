<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    const NB_USER = 20;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserFixtures constructor
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)

    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@fake.com')
                ->setUsername('username'.$i)
                ->setFirstname('firstname '.$i)
                ->setLastname('lastname')
                ->setRoles(["ROLE_USER"])
                ->setPassword($this->encoder->encodePassword($user, "password$i"));
            $this->addReference("user$i", $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
