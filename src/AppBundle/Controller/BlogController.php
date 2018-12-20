<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class BlogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $articles = [];



        return $this->render('index.html.twig', array(
            "articles" => $articles

        ));
    }

    /**
     * @Route("/create", name="goto_create")
     */
    public function createAction(Request $request)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('photo_url', UrlType::class)
            ->add('save', SubmitType::class, array('label' => 'Publier'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();

            dump($article);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('task_success');
        }

        return $this->render('create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/post/{arg}")
     *
     */
    public function postAction($arg)
    {
        return $this->render('post.html.twig', array(
            "arg" => $arg
        ));
    }

    /**
     * @Route("/populate")
     */
    public function populateAction()
    {
        $articles = [];



        return $this->render('index.html.twig', array(
            "articles" => $articles

        ));
    }

}
