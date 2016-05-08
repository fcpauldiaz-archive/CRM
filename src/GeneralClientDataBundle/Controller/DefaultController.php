<?php

namespace GeneralClientDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GeneralClientDataBundle:Default:index.html.twig', array('name' => $name));
    }
}
