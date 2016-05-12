<?php

namespace GeneralClientDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GeneralClientDataBundle\Entity\Correo;
use GeneralClientDataBundle\Form\CorreoType;

/**
 * Correo controller.
 *
 * @Route("/correo")
 */
class CorreoController extends Controller
{

    /**
     * Lists all Correo entities.
     *
     * @Route("/", name="correo")
     * @Method("GET")
     */
    public function indexAction()
    {
         $sql = " 
            SELECT  *
            FROM correo
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $correo = new Correo();
            $correo->setId($entity["id"]);
            $correo->setCorreoElectronico($entity["correo_electronico"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $correo;

        }
        return $this->render('GeneralClientDataBundle:Correo:indexCorreo.html.twig',
             array(
            'entities' => $entities,
        ));
    }
   

    /**
     * Displays a form to create a new Correo entity.
     *
     * @Route("/new", name="correo_new")
     * 
     * 
     */
    public function newAction(Request $request)
    {
        $entity = new Correo();
        $form   = $this->createForm(new CorreoType($this->getDoctrine()->getManager(), true), $entity);
        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('GeneralClientDataBundle:Correo:newCorreo.html.twig',
                 [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ]
            );
        }
        $sql = " 
            INSERT INTO correo
            VALUES (nextval('correo_id_seq'), ?, null)
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $form->getData()->getCorreoElectronico());
        $stmt->execute();
        $res = $stmt->fetchAll();
        $sql = " 
            Select currval('correo_id_seq')
            FROM correo
            LIMIT 1;
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->redirect($this->generateUrl('correo_show', array('id' => $res[0]["currval"])));


         
    }

    /**
     * Finds and displays a Correo entity.
     *
     * @Route("/{id}", name="correo_show")
     * @Method("GET")
     * @Template("GeneralClientDataBundle:Correo:showCorreo.html.twig")
     */
    public function showAction($id)
    {
         $sql = " 
            SELECT  *
            FROM correo
            WHERE id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entity = new Correo();
        $entity->setId($res[0]["id"]);
        $entity->setCorreoElectronico($res[0]["correo_electronico"]);

        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Correo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Correo entity.
     *
     * @Route("/{id}/edit", name="correo_edit")
     * @Method("GET")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $sql = " 
            SELECT  *
            FROM correo
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $correo = new Correo();
            $correo->setId($entity["id"]);
            $correo->setCorreoElectronico($entity["correo_electronico"]);
            $correo->setCliente($entity["cliente_id"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $correo;

        }
        $entity = $entities[0];

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Correo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GeneralClientDataBundle:Correo:editCorreo.html.twig', 
            [
              'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

            ]);
       
    }

    /**
    * Creates a form to edit a Correo entity.
    *
    * @param Correo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Correo $entity)
    {
        $form = $this->createForm(new CorreoType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('correo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => ['class' => 'btn btn-primary']));

        return $form;
    }
    /**
     * Edits an existing Correo entity.
     *
     * @Route("/{id}", name="correo_update")
     * @Method("PUT")
     * @Template("GeneralClientDataBundle:Correo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $sql = " 
            SELECT  *
            FROM correo
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $correo = new Correo();
            $correo->setId($entity["id"]);
            $correo->setCorreoElectronico($entity["correo_electronico"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $correo;

        }
        $entity = $entities[0];
       

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Correo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

             $correo = $request->request->get('correo')['correoElectronico'];
           
            $em = $this->getDoctrine()->getManager();
            $sql = " 
                UPDATE  correo
                SET correo_electronico = ?
                Where id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $correo);
            $stmt->bindValue(2, $id);
            $stmt->execute();
           
            return $this->redirect($this->generateUrl('correo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Correo entity.
     *
     * @Route("/{id}", name="correo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
;
        if ($form->isValid()) {
            $sql = " 
                DELETE FROM correo
                WHERE id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
        }

        return $this->redirect($this->generateUrl('correo'));
    }

    /**
     * Creates a form to delete a Correo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('correo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar','attr' => ['class' => 'btn btn-danger']))
            ->getForm()
        ;
    }
}
