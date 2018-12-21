<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{

    /**
     * @Route("/users")
     */
    public function listUsersAction()
    {

//
//        return $this->render('index.html.twig', array(
//            "articles" => $paginator,
//            "nb_articles" => $nb_articles
//        ));
    }
}
