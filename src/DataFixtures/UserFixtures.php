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
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
//        $user = new User();
//        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        for($i = 0; $i<self::NB_USER; $i++) {
            $user = new User();

            $user->setUsername("username$i")
                ->setEmail("email$i@mail.com")
                ->setFirstname("firstname$i")
                ->setLastname("lastname$i")
                ->setRoles(["ROLE_USER"])
                ->setPassword($this->encoder->encodePassword($user, "password$i"));

            $this->addReference("user$i", $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}