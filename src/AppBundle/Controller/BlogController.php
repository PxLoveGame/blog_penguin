<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BlogController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/post")
     */
    public function postAction()
    {
        return $this->render('post.html.twig', array(
            // ...
        ));
    }

}
