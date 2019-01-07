<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
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
     * @Route("/post/create", name="goto_create")
     */
    public function createAction(Request $request)
    {
        $article = new Article();

        // search form
        $searchForm = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => '' ))
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->redirect($this->generateUrl('search', array('name' => $searchForm['title']->getData())));
        }

        // create form
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('content', TextareaType::class , array('label' => 'Contenu'))
            ->add('photo_url', FileType::class, array('label' => 'Image', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Publier'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();
            $photo = $form['photo_url']->getData();

            if($photo == null){
//                $photo = 'img/default_picture.jpg';
                $photo = null;
                $article->setPhotoUrl($photo);
            }
            else {
                $directory = 'img/articles';
                $fileName = md5(uniqid()).'.'.$photo->guessExtension();

                $article->setPhotoUrl($directory.'/'.$fileName);
                $photo->move($directory, $fileName);
            }

//            dump($article);

            $date = new DateTime();
            $article->setPublished($date);

//            dump($article);

            $article->setUrl($article->getTitle());
            $user = $this->getUser();
            $article->setAuthor($user->getUsername());



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'article a bien été créé !'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('create.html.twig', array(
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView()
        ));
    }

    /**
     * @Route("/post/{arg}")
     */
    public function postAction($arg, Request $request)
    {

        $articlesRepository = $this->getDoctrine()->getRepository(Article::class);
        $article = $articlesRepository->findByName($arg)[0];

        // search form
        $searchForm = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => '' ))
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();


        // Formulaire pour poster un com'
        $comment = new Comment();
        $comment_form = $this->createFormBuilder($comment)
            ->add('content', TextareaType::class , array('label' => 'Ecrire un commentaire', 'required' => true))
//            ->add('photo_url', FileType::class, array('label' => 'Image', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Publier'))
            ->getForm();

//        dump($request);
//        dump($request->request);
//        dump('has form : ' . $request->request->has('form'));

        if ($request->request->has('form')){
            $comment_form->handleRequest($request);

        } else {

            $searchForm->handleRequest($request);
        }



        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->redirect($this->generateUrl('search', array('name' => $searchForm['title']->getData())));
        }

        // Verifier si il est possible d'editer/supprimer le post par l'utilisateur actuel
        $user = $this->getUser();
        $isAdmin = $user && in_array("ROLE_SUPER_ADMIN", $user->getRoles());
        $isAuthor = $user && $user->getUsername() == $article->getAuthor();
        $canManage = $isAdmin || $isAuthor;

        // Formulaire pour poster un commentaire
        $commentsRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentsRepository->getArticleComments($article->getId());


        $commentsCount = count($comments);


        if ($comment_form->isSubmitted() && $comment_form->isValid()) {

            if($comment_form['content']->getData() != null){
                $comment->setContent($comment_form['content']->getData());
            }

            $comment->setPost($article->getId());
            $comment->setAuthor($user->getUsername());
            $comment->setPublished(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_post', array("arg" => $article->getTitle() ));
        }



        return $this->render('post.html.twig', array(
            "commentForm"=> $comment_form->createView(),
            "commentsCount" => $commentsCount,
            "comments" => $comments,
            "canManage" => $canManage,
            "arg" => $arg,
            'article'=> $article,
            'searchForm' => $searchForm->createView()
        ));
    }

    /**
     * @Route("/post/edit/{id}")
     */
    public function EditPost($id, Request $request)
    {

        $article = new Article();

        // search form
        $searchForm = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => '' ))
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->redirect($this->generateUrl('post', array('arg' => $searchForm['title']->getData())));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if(!$article) {
            throw $this->createNotFoundException('No article found for id', $id);
        }

        $user = $this->getUser();
        $isAdmin = $user && in_array("ROLE_SUPER_ADMIN", $user->getRoles());
        $isAuthor = $user && $user->getUsername() == $article->getAuthor();
        $canManage = $isAdmin || $isAuthor;

        if (!$canManage){
            return $this->redirectToRoute('homepage');
        }

        $data_form = new Article();
        $data_form->setContent($article->getContent());

        $form = $this->createFormBuilder($data_form)
            ->add('content', TextareaType::class , array('label' => 'Contenu', 'required' => false))
            ->add('photo_url', FileType::class, array('label' => 'Image', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Modifier'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form['content']->getData() != null){
                $article->setContent($form['content']->getData());
            }

            if($form['photo_url']->getData()){

                $photo = $form['photo_url']->getData();

                $directory = 'img/articles';
                $fileName = md5(uniqid()).'.'.$photo->guessExtension();
                $photo->move($directory, $fileName);

                $article->setPhotoUrl($directory.'/'.$fileName);
            }

            $this->addFlash(
                'success',
                'L\'article a bien été modifié !'
            );


            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('edit.html.twig', array(
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView(),
            'article' => $article
        ));
    }

    /**
     * @Route("/post/delete/{id}")
     * @param $id
     */
    public function deletePost($id){
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        $user = $this->getUser();
        $isAdmin = $user && in_array("ROLE_SUPER_ADMIN", $user->getRoles());
        $isAuthor = $user && $user->getUsername() == $article->getAuthor();
        $canManage = $isAdmin || $isAuthor;

        if(!$article || !$canManage) {

            $this->addFlash(
                'notice',
                'Impossible de supprimer l\'article !'
            );

            throw $this->createNotFoundException('No article found for id', $id);
        }

        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'l\'article à bien été supprimé !'
        );

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/post/search/{name}", name="search" )
     */
    public function searchByName($name, Request $request){

        $article = new Article();

        // search form
        $searchForm = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => '' ))
            ->add('save', SubmitType::class, array('label' => 'Go!'))
            ->getForm();

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->redirect($this->generateUrl('search', array('name' => $searchForm['title']->getData())));
        }

        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findByName($name);

        $article = $repository->findOneBy(['title' => $name]);

        if (!$articles){
            $this->addFlash(
                'error',
                'Aucun article n\'a été trouvé pour le titre : '.$name
            );

            return $this->redirectToRoute('homepage');
        }else {
            $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array('label' => '' ))
                ->add('save', SubmitType::class, array('label' => 'Go!'))
                ->getForm();

            return $this->render('index.html.twig', array(
                "articles" => $articles,
                "nb_articles" => count($articles),
                "nb_pages" => 1,
                "current_page" => 1,
                "form" => $form->createView()
            ));
        }
    }
}
