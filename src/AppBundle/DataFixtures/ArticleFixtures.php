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
        for ($i = 0; $i < 7; $i++) {
            $article = new Article();

            $article->setTitle('article '.$i);
            $article->setContent('Lorem ipsum dolor sit amet et consecuetir');
            $date = mt_rand(1, 28) . '-' . mt_rand(1, 12) . '-' . mt_rand(2013, 2018);
            $article->setPublished( new \DateTime($date) );
            $article->setPhotoUrl('img/default_picture.jpg');


            $manager->persist($article);
        }

        $article = new Article();

        $article->setTitle('Les restes d’un méga-manchot découverts en Antarctique');
        $article->setContent('L’île Seymour est un petit lopin de terre au large de l’Antarctique connu pour être une base de l’armée argentine et un havre pour les manchots de la région. C’est là-bas que les spécialistes se rendent pour étudier cette espèce, et c’est là qu’une équipe de chercheurs a découvert les ossements de ce qui pourrait être, ni plus ni moins, le plus grand manchot de tous les temps (à ne pas confondre avec le pingouin, qui peut voler et vit dans l’hémisphère Nord).
Baptisé Palaeeudyptes klekowskii, il aurait vécu à l’éocène – il y a entre 55 millions et 37 millions d’années – , selon l’équipe de l’Institut antarctique argentin qui l’a découvert. Celle-ci annonce dans un article (lien payant) publié dans une revue de paléontologie que ce « méga-manchot » faisait presque la taille d’un basketteur, soit près de 2,01 mètres du pied au bec, et pesait entre 114 et 116 kilos.

A comparer avec le précédent record de spécimen connu chez les sphenisciformes – 1,66 mètre pour 82,3 kilos – et au 1,16 mètre en moyenne de la plus grande espèce encore vivante, le manchot empereur, qui, d’un seul coup, ne se sent plus si grand.');
        $article->setPublished( new \DateTime() );
        $article->setPhotoUrl('img/default_picture.jpg');

        $manager->persist($article);

        $manager->flush();
    }
}