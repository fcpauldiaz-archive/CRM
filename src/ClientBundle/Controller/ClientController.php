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

    public function indexAction(Request $request)
    {

    }


	 /**
     * Displays a form to create a new Client entity.
     *
     * @Route("/new", name="cliente_new")
     */

    public function newAction(Request $request)
    {
        $entity = new Client();

        $camposDinamicos = $this->getNombreTipoColumnas();

        $form   = $this->createForm(
            new ClientType($this->getDoctrine()->getManager(), $camposDinamicos)
        );

        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('ClientBundle:Client:newClient.html.twig',
                 [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ]
            );
        }

        $data = $form->getData();

        $fechaNacimiento = $data['fechaNacimiento'];
        $nit = $data['nit'];
        $frecuente = $data['frecuente'];
        $nombres = $data['nombres'];
        $apellidos = $data['apellidos'];
        $estadoCivil = $data['estadoCivil'];
        $tipoMembresia = $data['tipoMembresia'];
        $correo = $data['correo'];
        $sexo = $data['sexo'];
        $profesion = $data['profesion'];
        $dpi = $data['dpi'];
        $telefonos = $data['telefono'];
        $direccion = $data['direccion'];
        $imagen = $data['imageFile'];
        $nacionalidad = $data['nacionalidad'];
        $twitterUsername = $data['twitterUsername'];
        $usuario = $this->getUser();
        $client = new Client();
        $client->setImageFile($imagen);
        $client->uploadImage();
       

         $sql = " 
            INSERT INTO client
            VALUES (
            nextval('client_id_seq'), 
            :fecha,
            :nit,
            :frecuente,
            :nombres,
            :apellidos,
            :estado,
            :foto,
            :sexo,
            :profesion,
            :dpi,
            :nacion,
            :tipo,
            :usuario,
            :twit
            )
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
      
        $stmt->bindValue("nit", $nit );
        $stmt->bindValue("frecuente", $frecuente, \PDO::PARAM_BOOL );
        $stmt->bindValue("nombres", $nombres );
        $stmt->bindValue("apellidos", $apellidos );
        $stmt->bindValue("estado", $estadoCivil );
        $stmt->bindValue("foto", $client->getFotoCliente() );
        $stmt->bindValue("sexo", $sexo );
        $stmt->bindValue("profesion", $profesion );
        $stmt->bindValue("dpi", $dpi );
        $stmt->bindValue("nacion", $nacionalidad );
        $stmt->bindValue("twit", $twitterUsername );
        $stmt->bindValue("tipo", $tipoMembresia);
        $stmt->bindValue("fecha", $fechaNacimiento, 'datetime');
        $stmt->bindValue("usuario", $usuario->getId());

        $stmt->execute();

        $sql = " 
            Select currval('client_id_seq')
            FROM client
            LIMIT 1;
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();

       $sql = " 
            INSERT INTO direccion
            VALUES (
            nextval('direccion_id_seq'), 
            :direccion,
            :cliente
            )
            ";

        foreach($direccion as $dir) {
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue("direccion", $dir["d"]);
            $stmt->bindValue("cliente", $res[0]["currval"]);

            $stmt->execute();
        }

        $sql = " 
            INSERT INTO correo
            VALUES (
            nextval('correo_id_seq'), 
            :correo,
            :cliente
            )
            ";
        foreach($correo as $mail) {
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue("correo", $mail["correoElectronico"]);
            $stmt->bindValue("cliente", $res[0]["currval"]);

            $stmt->execute();
        }

        $sql = " 
            INSERT INTO telefono
            VALUES (
            nextval('telefono_id_seq'), 
            :telefono,
            :cliente
            )
            ";
        foreach($telefonos as $phone) {
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue("telefono", $phone["numeroTelefono"]);
            $stmt->bindValue("cliente", $res[0]["currval"]);

            $stmt->execute();
        }

        $formData = $form->getData();
        foreach ($camposDinamicos as $campo) {
            $value = $formData[$campo['nombre']];
            $campo_dinamico_id = $campo['campo_id'];
            $cliente_id = $this->getLastClienteIdInserted();

            $insertValue = "";
            switch ($campo['tipo']) {
                case 'number':
                    $insertValue = (string) $value;
                    break;
                case 'checkbox':
                    if ($value === true) {
                        $insertValue = "true";
                        break;
                    }
                    $insertValue = "false";
                    break;
                case 'text':
                    $insertValue = $value;
                    break;
                case 'date':
                    $insertValue = $value->format('Y-m-d H:m:s');
                    break;
            }

            $this->insertCampoDinamico($insertValue, $campo_dinamico_id, $cliente_id);
        }
        die();
    }

    private function getNombreTipoColumnas()
    {
        $conexionDB = $this->get('database_connection'); // Conexión con la BD
        $sql = "SELECT * FROM campo_dinamico";
        $stmt = $conexionDB->prepare($sql);
        $stmt->execute();

        $camposDinamicos = $stmt->fetchAll();

        $returnArray = [];

        foreach ($camposDinamicos as $campo) {
            // processTipoColumna

            $tipo = $this->getTipoColumnaById($campo['tipo_columna_id']);

            $returnArray[] = [
                'nombre' => $campo['nombre'],
                'tipo' => $this->processTipoColumna($tipo),
                'campo_id' => $campo['id']
            ];
        }

        return $returnArray;
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

    private function processTipoColumna($tipo)
    {
        $returnTipo = "";

        switch ($tipo) {
            case 'INTEGER':
                $returnTipo = 'number';
                break;
            case 'VARCHAR(50)':
                $returnTipo = 'text';
                break;
            case 'DOUBLE PRECISION':
                $returnTipo = 'number';
                break;
            case 'DATE':
                $returnTipo = 'date';
                break;
            case 'BOOLEAN':
                $returnTipo = 'checkbox';
                break;
            default:
                throw new \LogicException('Tipo de columna no reconocido');
                break;
        }

        return $returnTipo;
    }

    private function insertCampoDinamico($valor, $campo_dinamico_id, $cliente_id)
    {
        $conexionDB = $this->get('database_connection');
        $sql = "INSERT INTO valor_dinamico VALUES (nextval('valor_dinamico_id'), ?, ?, ?)";

        $stmt = $conexionDB->prepare($sql);
        $stmt->bindValue(1, $valor);
        $stmt->bindValue(2, $campo_dinamico_id);
        $stmt->bindValue(3, $cliente_id);

        $stmt->execute();
    }

    private function getLastClienteIdInserted()
    {
        $conexionDB = $this->get('database_connection');
        $sql = "SELECT id FROM client ORDER BY id DESC LIMIT 1;";

        $stmt = $conexionDB->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll()[0]['id'];
    }
}
