<?php

namespace Modulo1Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Modulo1Bundle\Form\CampoDinamico\Crear as CampoDinamicoForm;

/**
 * @Route("/campo_dinamico/")
 */
class CampoDinamicoController extends Controller
{
    /**
     * @Template()
     * @Route("agregar/{nombreTabla}")
     */
    public function agregarCampoAction(Request $request, $nombreTabla)
    {
        $form = $this->createForm(new CampoDinamicoForm());

        if (!$form->handleRequest($request)->isValid()) {
            return [
                'nombreTabla' => $nombreTabla,
                'form' => $form->createView()
            ];
        }


        try {
            // Preparé el query para solo agregar el tipo que quiero
            $conexionDB = $this->get('database_connection'); // Conexión con la BD
            $sql = "SELECT id FROM tipo_columna"; // WHERE tipo = ?";
            $stmt = $conexionDB->prepare($sql); // Preparar el SQL pasándolo a DQL y dejarlo listo para inyectarle los paráms..

            // Obtengo todos los campos del FORM
            $formData = $form->getData();

            //$stmt->bindValue(1, $formData['tipo']); // Inyectarle parámetros a query
            $stmt->execute(); // Ejecutar el query

            $idTipoColumna = $stmt->fetchAll(); // Procesar el resultado

            dump($idTipoColumna);
            die();

        } catch (Exception $e) {
            throw new \LogicException('Tipo de columna no reconocido');
        }
    }
}
