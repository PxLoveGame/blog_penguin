<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


/**
 *
 * Class ArticleFixture
 * @package AppBundle\Entity
 *
 * Génère des valeurs random d'Articles via DoctrineFixturesBundle
 */
class ArticleFixtures extends Fixture
{


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // create 20 articles! Bam!
        for ($i = 0; $i < 20; $i++) {
            $article = new Article();

            $article->setTitre('article '.$i);
            $article->setContent('Lorem ipsum dolor sit amet et consecuetir');
            $date = mt_rand(1, 28) . '-' . mt_rand(1, 12) . '-' . mt_rand(2013, 2018);
            $article->setPublished( new \DateTime($date) );
            $article->setPhotoUrl('img/penguins-family.jpg');


            $manager->persist($article);
        }

        $manager->flush();
    }
}