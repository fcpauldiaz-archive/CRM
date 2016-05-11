<?php

namespace ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ClientBundle\Entity\Client;
use ClientBundle\Form\ClientType;

/**
 * Client controller.
 *
 * @Route("/cliente")
 */
class ClientController extends Controller
{

	 /**
     * Displays a form to create a new Client entity.
     *
     * @Route("/new", name="cliente_new")
     */
    public function newAction(Request $request)
    {
        $entity = new Client();
        $form   = $this->createForm(new ClientType(), $entity);
        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('ClientBundle:Client:newClient.html.twig',
                 [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ]
            );
        }



    }

}