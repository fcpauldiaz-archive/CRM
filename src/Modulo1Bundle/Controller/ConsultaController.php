<?php

namespace Modulo1Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * @Route("/consulta/")
 */
class ConsultaController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Modulo1Bundle:Default:index.html.twig', array('name' => $name));
    }
    /**
     * @Route("show")
     * 
     */
    public function showClientsAction()
    {
        $conexionDB = $this->get('database_connection');

        $sql = "SELECT * FROM client";

        $stmt = $conexionDB->prepare($sql); // Prepara el SQL a DQL para dejarlo listo para inyectarle los parametros
        
        $stmt->execute();

        $clients = $stmt->fetchAll();
        // dump($clients);
        // die();

        return $this->render('Modulo1Bundle:client:showClient.html.twig', array('clients' => $clients));
    }
}
