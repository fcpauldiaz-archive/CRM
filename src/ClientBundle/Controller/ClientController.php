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
        $form   = $this->createForm(new ClientType($this->getDoctrine()->getManager()));
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

}