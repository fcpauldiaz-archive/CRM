<?php

namespace Modulo1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GeneralClientDataBundle\Entity\Direccion;
use Modulo1Bundle\Form\DireccionType;
/**
 * Direccion controller.
 *
 * @Route("/direccion")
 */
class DireccionController extends Controller
{
   /**
     * Lists all direccion entities.
     *
     * @Route("/", name="direccion")
     * @Method("GET")
     */
    public function indexAction()
    {
         $sql = " 
            SELECT  *
            FROM direccion
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Direccion:indexDirecciones.html.twig',
             array(
            'entities' => $res,
        ));
    }

   	 /**
     * Displays a form to create a new Direccion entity.
     *
     * @Route("/new", name="direccion_new")
     * 
     * 
     */
    public function newAction(Request $request)
    {
        $entity = new Direccion();
        $form   =  $this->createForm(new DireccionType($this->getDoctrine()->getManager(),true));
        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('Modulo1Bundle:Direccion:newDireccion.html.twig',
                 [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ]
            );
        }
        $sql = " 
            INSERT INTO direccion
            VALUES (nextval('direccion_id_seq'), ?, ?)
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $form->getData()['direccion']);
        $stmt->bindValue(2, $form->getData()['cliente']);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $sql = " 
            Select currval('direccion_id_seq')
            FROM direccion
            LIMIT 1;
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->redirect($this->generateUrl('direccion_show', array('id' => $res[0]["currval"])));
    }

    /**
     * Finds and displays a Direccion entity.
     *
     * @Route("/{id}", name="direccion_show")
     * @Method("GET")
     * @Template("Modulo1Bundle:Direccion:showDireccion.html.twig")
     */
    public function showAction($id)
    {
         $sql = " 
            SELECT  *
            FROM direccion
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
     * Displays a form to edit an existing Direccion entity.
     *
     * @Route("/{id}/edit", name="direccion_edit")
     * @Method("GET")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $sql = " 
            SELECT  *
            FROM direccion
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $direccion = new Direccion();
            $direccion->setId($entity["id"]);
            $direccion->setDireccion($entity["direccion"]);
            $direccion->setCliente($entity["cliente_id"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $direccion;

        }
        $entity = $entities[0];
       

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Modulo1Bundle:Direccion:editDireccion.html.twig', 
            [
              'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

            ]);
       
    }
    /**
    * Creates a form to edit a Direccion entity.
    *
    * @param Direccion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Direccion $entity)
    {
        $form = $this->createForm(new DireccionType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('direccion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => ['class' => 'btn btn-primary']));

        return $form;
    }
    /**
     * Edits an existing Correo entity.
     *
     * @Route("/{id}", name="direccion_update")
     * @Method("PUT")
     * @Template("Modulo1Bundle:direccion:editDireccion.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $sql = " 
            SELECT  *
            FROM direccion
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $direccion = new Direccion();
            $direccion->setId($entity["id"]);
            $direccion->setDireccion($entity["direccion"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $direccion;

        }
        $entity = $entities[0];
       

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Correo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

             $direccion = $request->request->get('direccion')['direccion'];
           
            $em = $this->getDoctrine()->getManager();
            $sql = " 
                UPDATE  direccion
                SET direccion = ?
                Where id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $direccion);
            $stmt->bindValue(2, $id);
            $stmt->execute();
           
            return $this->redirect($this->generateUrl('direccion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a direccion entity.
     *
     * @Route("/{id}", name="direccion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
;
        if ($form->isValid()) {
            $sql = " 
                DELETE FROM direccion
                WHERE id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
        }

        return $this->redirect($this->generateUrl('direccion'));
    }
     /**
     * Creates a form to delete a Direccion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('direccion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar','attr' => ['class' => 'btn btn-danger']))
            ->getForm()
        ;
    }
}
