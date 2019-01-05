<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


/**
 *
 * Class ArticleFixture
 * @package AppBundle\Entity
 *
 * Génère des valeurs random d'Articles via DoctrineFixturesBundle
 */
class UsersFixtures extends Fixture
{


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // create 20 users
        for ($i = 0; $i < 5; $i++) {
            $user = new User();

            $user->setEmail("user".$i."@example.com");
            $user->setUsername("user".$i);
            $user->setPlainPassword($i);
            $user->setRoles([User::ROLE_DEFAULT]);
            $manager->persist($user);
            $user->setEnabled(true);
        }

        $admin = new User();
        $admin->setUsername("admin");
        $admin->setPlainPassword("admin");
        $admin->setEmail("admin@example.com");
        $admin->setRoles([User::ROLE_SUPER_ADMIN]);
        $admin->setEnabled(true);
        $manager->persist($admin);



        $manager->flush();
    }
}