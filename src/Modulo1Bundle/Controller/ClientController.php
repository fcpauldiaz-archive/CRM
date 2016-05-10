<?php

namespace Modulo1Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Modulo1Bundle:Default:index.html.twig', array('name' => $name));
    }

    public function showClientsAction()
    {
        $conexionDB = $this->get('database_connection');

        $sql = "SELECT * FROM xTabla WHERE id = ? AND status = ?";

        $stmt = $conn->prepare($sql); // Prepara el SQL a DQL para dejarlo listo para inyectarle los parametros
        
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $status);
        
        $stmt->execute();
    }
}
