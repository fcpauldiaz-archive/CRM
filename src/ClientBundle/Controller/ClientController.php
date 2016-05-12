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
     * @Route("/", name="cliente")
     */
    public function indexAction(Request $request)
    {
        $sql = " 
            SELECT * 
            FROM client
            ";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll();

        
        $returnArray = [];
        foreach($resultado as $cliente ){
            $entity = [];
            $id = $cliente["id"];
            $membresia_id = $cliente["tipo_membresia_id"];
            dump($id);
            $sqlCorreo = " 
                SELECT  correo_electronico 
                FROM correo
                WHERE cliente_id = ?
                ";

            $sqlTelefono = " 
                SELECT numero_telefono 
                FROM telefono t
                WHERE cliente_id = ?
                ";

            $sqlDireccion = " 
                SELECT  direccion 
                FROM direccion
                WHERE cliente_id = ?
                ";

            $sqlMembresia = " 
                SELECT  tipo_membresia 
                FROM tipo_membresia
                WHERE id = ?
                ";

             $sqlUsuario = " 
                SELECT username 
                FROM usuario u
                INNER JOIN client c on u.id = c.usuario_id
                WHERE c.id = ?
                ";
                //obtener correos
                $em = $this->getDoctrine()->getManager();
                $stmt = $em->getConnection()->prepare($sqlCorreo);
                $stmt->bindValue(1, $id );
                $stmt->execute();
                $correos = $stmt->fetchAll();
                //obtener direcciones
                $stmt = $em->getConnection()->prepare($sqlDireccion);
                $stmt->bindValue(1, $id );
                $stmt->execute();
                $direcciones = $stmt->fetchAll();
                //obtener telefonos
                $stmt = $em->getConnection()->prepare($sqlTelefono);
                $stmt->bindValue(1, $id);
                $stmt->execute();
                $telefonos = $stmt->fetchAll();
                //obtener membresia
                $stmt = $em->getConnection()->prepare($sqlMembresia);
                $stmt->bindValue(1, $membresia_id );
                $stmt->execute();
                $membresia = $stmt->fetchAll();
                //obtener usuario
                $stmt = $em->getConnection()->prepare($sqlUsuario);
                $stmt->bindValue(1, $id );
                $stmt->execute();
                $usuario = $stmt->fetchAll();

                $entity[] = $id;
                $entity[] = $cliente["nit"];
                $entity[] = $cliente["frecuente"];
                $entity[] = $cliente["nombres"];
                $entity[] = $cliente["apellidos"];
                $entity[] = $cliente["estado_civil"];
                $entity[] = $cliente["foto_cliente"];
                $entity[] = $cliente["sexo"];
                $entity[] = $cliente["nacionalidad"];
                $entity[] = $cliente["twitter_username"];
                $entity[] = $cliente["fecha_nacimiento"];
                if ($correos) {
                    $corr = [];
                    foreach($correos as $mail) {
                        $corr[] = $mail["correo_electronico"];
                    }
                    $entity[] = $corr;
                }
                if ($direcciones) {
                    $dirs = [];
                    foreach($direcciones as $adress) {
                        $dirs[] = $adress["direccion"];
                    }
                    $entity[] = $dirs;
                }
                if ($telefonos) {
                    $tels = [];
                    foreach($telefonos as $telefono) {
                        $tels[] = $telefono["numero_telefono"];
                    }
                    $entity[] = $tels;
                }
                if ($membresia) {
                    $entity[] = $membresia[0]["tipo_membresia"];
                }
                if ($usuario) {
                    $entity[] = $usuario[0]["username"];
                }
            $returnArray[] = $entity;
                
        }
        
        return $this->render('ClientBundle:Client:indexClient.html.twig',[
            'clientes' => $returnArray,
        ]);

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
            :twit,
            :tipo,
            :fecha,
            :usuario

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
                'tipo' => $this->processTipoColumna($tipo)
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
}
