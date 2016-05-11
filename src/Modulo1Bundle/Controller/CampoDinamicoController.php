<?php

namespace Modulo1Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Modulo1Bundle\Form\CampoDinamico\Crear as CampoDinamicoForm;

class CampoDinamicoController extends Controller
{
    public function agregarCampoDinamicoAction(Request $request, $nombreTabla)
    {
        $form = $this->createForm(new CampoDinamicoForm());

        if (!$form->handleRequest($request)->isValid()) {
            return [
                'nombreTabla' => $nombreTabla
            ];
        }


        try {
            // Preparé el query para solo agregar el tipo que quiero
            $conexionDB = $this->get('database_connection'); // Conexión con la BD
            $sql = "SELECT id FROM tipo_columna WHERE tipo = ?";
            $stmt = $conn->prepare($sql); // Preparar el SQL pasándolo a DQL y dejarlo listo para inyectarle los paráms..

            // Obtengo todos los campos del FORM
            $formData = $form->getData();

            $stmt->bindValue(1, $formData['tipo']); // Inyectarle parámetros a query
            $stmt->execute(); // Ejecutar el query

            $idTipoColumna = $stmt->fetchAll(); // Procesar el resultado

        } catch (Exception $e) {
            throw new \LogicException('Tipo de columna no reconocido');
        }
    }
}
