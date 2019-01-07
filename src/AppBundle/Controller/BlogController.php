<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->indexActionPage(1, $request);
    }

    /**
     * @Route("/{page}")
     */
    public function indexActionPage($page,Request $request)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => '' ))
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('search', array('name' => $form['title']->getData())));
        }

        $articles_count= $this->getDoctrine()
        ->getRepository('AppBundle:Article')
        ->countPublishedArticles();

        $nb_pages = ceil($articles_count / 3);

        $article_repo = $this->getDoctrine()->getRepository('AppBundle:Article');
        $articles = $article_repo->getArticles($page);

        return $this->render('index.html.twig', array(
            "articles" => $articles,
            "nb_articles" => $articles_count,
            "nb_pages" => $nb_pages,
            "current_page" => $page,
            "form" => $form->createView()
        ));
    }

}
