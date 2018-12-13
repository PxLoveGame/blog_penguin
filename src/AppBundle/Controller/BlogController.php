<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
    public function createAction()
    {
        return $this->render('create.html.twig', array());
    }


    public function form_createAction(Request $request)
    {
        $article = new Article();


        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('photo_url', UrlType::class)
            ->add('save', SubmitType::class, array('label' => 'Publier'))
            ->getForm();


        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            dump($data);
            $article->setTitre($data);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('task_success');

        }

        return $this->render('default/create.html.twig', array(
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
