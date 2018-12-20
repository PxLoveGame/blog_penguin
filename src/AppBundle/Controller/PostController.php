<?php

namespace AppBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends Controller
{

    /**
     * @Route("/create", name="goto_create")
     */
    public function createAction(Request $request)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('content', TextareaType::class , array('label' => 'Contenu'))
            ->add('photo_url', FileType::class, array('label' => 'Image'))
            ->add('save', SubmitType::class, array('label' => 'Publier'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();
            $photo = $form['photo_url']->getData();

            dump($article);

            $date = new DateTime();
            $article->setPublished($date->format('Y-m-d H:i:s'));

            $article->setUrl($article->getTitle());

            $directory = 'img/articles';
            $fileName = md5(uniqid()).'.'.$photo->guessExtension();

            $article->setPhotoUrl($directory.'/'.$fileName);
            $photo->move($directory, $fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
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


}
