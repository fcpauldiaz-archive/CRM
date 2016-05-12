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
     * @Route("agregar/")
     */
    public function agregarCampoAction(Request $request)
    {
        $form = $this->createForm(new CampoDinamicoForm());

        if (!$form->handleRequest($request)->isValid()) {
            return [
                'form' => $form->createView()
            ];
        }

        try {
            /****    Query para buscar id de tipo_columna   ******/

            // Preparé el query para solo agregar el tipo que quiero
            $conexionDB = $this->get('database_connection'); // Conexión con la BD
            $sql = "SELECT id FROM tipo_columna  WHERE tipo = ?";
            $stmt = $conexionDB->prepare($sql); // Preparar el SQL pasándolo a DQL y dejarlo listo para inyectarle los paráms..

            // Obtengo todos los campos del FORM
            $formData = $form->getData();

            $stmt->bindValue(1, $formData['tipo']);
            $stmt->execute();
            $idTipoColumna = $stmt->fetchAll();

            /****    Query para verificar que no exista campo con ese nombre   ******/

            $sql = "SELECT * FROM campo_dinamico  WHERE nombre = ?";
            $stmt = $conexionDB->prepare($sql);

            $stmt->bindValue(1, $formData['nombre']);
            $stmt->execute();
            $columnaDinamica = $stmt->fetchAll();

            if ($columnaDinamica) {
                $this->get('braincrafted_bootstrap.flash')->error(
                    sprintf(
                        'Error: El campo %s ya existe',
                        $formData['nombre']
                    )
                );

                return [
                    'form' => $form->createView()
                ];
            }

            /****    Query para ingresar a la base de datos   ******/

            $sql = "INSERT INTO campo_dinamico VALUES (nextval('campo_dinamico_id'), ?, ?)";
            $stmt = $conexionDB->prepare($sql);

            $stmt->bindValue(1, $formData['nombre']);
            $stmt->bindValue(2, (int) $idTipoColumna[0]['id']);

            $stmt->execute();

            return $this->redirect(
                $this->generateUrl("modulo1_campodinamico_listar", [])
            );

        } catch (Exception $e) {
            throw new \LogicException('Tipo de columna no reconocido');
        }
    }

    /**
     * @Template()
     * @Route("listar/")
     */
    public function listarAction()
    {
        $conexionDB = $this->get('database_connection'); // Conexión con la BD
        $sql = "SELECT * FROM campo_dinamico";
        $stmt = $conexionDB->prepare($sql);
        $stmt->execute();

        $camposDinamicos = $stmt->fetchAll();

        $returnArray = [];

        foreach ($camposDinamicos as $campo) {
            $returnArray[] = [
                'id' => $campo['id'],
                'nombre' => $campo['nombre'],
                'tipo' => $this->getTipoColumnaById($campo['tipo_columna_id'])
            ];
        }

        // dump($returnArray);
        // die();

        return [
            'campos' => $returnArray
        ];
    }

    private function getTipoColumnaById($id)
    {
        $conexionDB = $this->get('database_connection');
        $sql = "SELECT tipo FROM tipo_columna  WHERE id = ?";
        $stmt = $conexionDB->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        return $stmt->fetchAll()[0]['tipo'];
    }
}
