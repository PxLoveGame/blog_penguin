<?php

namespace AppBundle\Controller;

use AppBundle\Repository\ArticleRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->indexActionPage(1);
    }

    /**
     * @Route("/{page}")
     */
    public function indexActionPage($page)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('a')
            ->from(Article::class, 'a')
            ->orderBy('a.published', 'DESC');

        $query = $entityManager->createQuery($queryBuilder->getQuery())
            ->setFirstResult(0)
            ->setMaxResults(5);

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $nb_articles = count($paginator);

        return $this->render('index.html.twig', array(
            "articles" => $paginator,
            "nb_articles" => $nb_articles
        ));
    }
}
