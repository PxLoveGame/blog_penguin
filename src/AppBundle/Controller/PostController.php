<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }


//    /**
//     * @param $arg
//     * @return \Symfony\Component\HttpFoundation\Response
//
//     */
//    public function postShortAction($arg)
//    {
//        return $this->render('post.html.twig', array(
//            "arg" => $arg
//        ));
//    }
}
