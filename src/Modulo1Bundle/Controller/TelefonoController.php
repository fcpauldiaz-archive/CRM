<?php

namespace Modulo1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GeneralClientDataBundle\Entity\Telefono;
use Modulo1Bundle\Form\TelefonoType;
/**
 * Telefono controller.
 *
 * @Route("/telefono")
 */
class TelefonoController extends Controller
{
   /**
     * Lists all telefono entities.
     *
     * @Route("/", name="telefono")
     * @Method("GET")
     */
    public function indexAction()
    {
         $sql = " 
            SELECT  *
            FROM telefono
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Telefono:indexTelefono.html.twig',
             array(
            'entities' => $res,
        ));
    }
    /**
     * Displays a form to create a new Telefono entity.
     *
     * @Route("/new", name="telefono_new")
     * 
     * 
     */
    public function newAction(Request $request)
    {
        $entity = new Telefono();
        $form   = $this->createForm(new TelefonoType($this->getDoctrine()->getManager()));
        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('Modulo1Bundle:Telefono:newTelefono.html.twig',
                 [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ]
            );
        }
        $sql = " 
            INSERT INTO telefono
            VALUES (nextval('telefono_id_seq'), ?, ?)
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $form->getData()['numeroTelefono']);
        $stmt->bindValue(2, $form->getData()['cliente']);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $sql = " 
            Select currval('telefono_id_seq')
            FROM telefono
            LIMIT 1;
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->redirect($this->generateUrl('telefono_show', array('id' => $res[0]["currval"])));
    }
    /**
     * Finds and displays a Telefono entity.
     *
     * @Route("/{id}", name="telefono_show")
     * @Method("GET")
     * @Template("Modulo1Bundle:Telefono:showTelefono.html.twig")
     */
    public function showAction($id)
    {
         $sql = " 
            SELECT  *
            FROM telefono
            WHERE id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $res,
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Displays a form to edit an existing Telefono entity.
     *
     * @Route("/{id}/edit", name="telefono_edit")
     * @Method("GET")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $sql = " 
            SELECT  *
            FROM telefono
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $telefono = new Telefono();
            $telefono->setId($entity["id"]);
            $telefono->setNumeroTelefono($entity["numero_telefono"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $telefono;

        }
        $entity = $entities[0];
       

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Modulo1Bundle:Telefono:editTelefono.html.twig', 
            [
              'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

            ]);
       
    }
    /**
    * Creates a form to edit a Telefono entity.
    *
    * @param Telefono $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Telefono $entity)
    {
        $form = $this->createForm(new TelefonoType($this->getDoctrine()->getManager()),$entity, array(
            'action' => $this->generateUrl('telefono_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => ['class' => 'btn btn-primary']));

        return $form;
    }
    /**
     * Edits an existing telefono entity.
     *
     * @Route("/{id}", name="telefono_update")
     * @Method("PUT")
     * @Template("Modulo1Bundle:Telefono:editTelefono.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $sql = " 
            SELECT  *
            FROM telefono
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $telefono = new Telefono();
            $telefono->setId($entity["id"]);
            $telefono->setNumeroTelefono($entity["numero_telefono"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $telefono;

        }
        $entity = $entities[0];
       

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Telefono entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

             $telefono = $request->request->get('numero_telefono')['numero_telefono'];
           
            $em = $this->getDoctrine()->getManager();
            $sql = " 
                UPDATE  telefono
                SET numero_telefono = ?
                Where id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $telefono);
            $stmt->bindValue(2, $id);
            $stmt->execute();
           
            return $this->redirect($this->generateUrl('telefono_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a telefono entity.
     *
     * @Route("/{id}", name="telefono_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
;
        if ($form->isValid()) {
            $sql = " 
                DELETE FROM telefono
                WHERE id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
        }

        return $this->redirect($this->generateUrl('telefono'));
    }
     /**
     * Creates a form to delete a telefono entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('telefono_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar','attr' => ['class' => 'btn btn-danger']))
            ->getForm()
        ;
    }
}
