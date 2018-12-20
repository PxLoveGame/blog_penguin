<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
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
        $articles_count= $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->countPublishedArticles();

        $article_repo = $this->getDoctrine()->getRepository('AppBundle:Article');
        $articles = $article_repo->getArticles($page);

        return $this->render('index.html.twig', array(
            "articles" => $articles,
            "nb_articles" => $articles_count
        ));
    }
}
