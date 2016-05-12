<?php

namespace Modulo1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Producto;
use Modulo1Bundle\Form\ProductoType;
/**
 * Producto controller.
 *
 * @Route("/producto")
 */
class ProductoController extends Controller
{
	/**
     * Lists all producto entities.
     *
     * @Route("/", name="producto")
     * @Method("GET")
     */
    public function indexAction()
    {
         $sql = " 
            SELECT  *
            FROM producto
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Producto:indexProducto.html.twig',
             array(
            'entities' => $res,
        ));
    }
    /**
     * Displays a form to create a new Producto entity.
     *
     * @Route("/new", name="producto_new")
     * 
     * 
     */
    public function newAction(Request $request)
    {
        $entity = new Producto();
        $form   =  $this->createForm(new ProductoType(), $entity);
        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('Modulo1Bundle:Producto:newProducto.html.twig',
                 [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ]
            );
        }
        $sql = " 
            INSERT INTO producto
            VALUES (nextval('direccion_id_seq'), ?)
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $form->getData()->getProducto());
        $stmt->execute();
        $res = $stmt->fetchAll();
        $sql = " 
            Select currval('direccion_id_seq')
            FROM producto
            LIMIT 1;
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->redirect($this->generateUrl('producto_show', array('id' => $res[0]["currval"])));
    }
    /**
     * Finds and displays a Producto entity.
     *
     * @Route("/{id}", name="producto_show")
     * @Method("GET")
     * @Template("Modulo1Bundle:Producto:showProducto.html.twig")
     */
    public function showAction($id)
    {
         $sql = " 
            SELECT  *
            FROM producto
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
     * Displays a form to edit an existing Producto entity.
     *
     * @Route("/{id}/edit", name="producto_edit")
     * @Method("GET")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $sql = " 
            SELECT  *
            FROM producto
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $producto = new Producto();
            $producto->setId($entity["id"]);
            $producto->setProducto($entity["producto"]);
            $entities[] = $producto;

        }
        $entity = $entities[0];
       

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Modulo1Bundle:Producto:editProducto.html.twig', 
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
    private function createEditForm(Producto $entity)
    {
        $form = $this->createForm(new ProductoType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('producto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => ['class' => 'btn btn-primary']));

        return $form;
    }
    /**
     * Edits an existing Correo entity.
     *
     * @Route("/{id}", name="producto_update")
     * @Method("PUT")
     * @Template("Modulo1Bundle:Producto:editProducto.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $sql = " 
            SELECT  *
            FROM producto
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $producto = new Producto();
            $producto->setId($entity["id"]);
            $producto->setProducto($entity["producto"]);
            $entities[] = $producto;

        }
        $entity = $entities[0];
       

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Producto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

             $producto = $request->request->get('producto')['producto'];
           
            $em = $this->getDoctrine()->getManager();
            $sql = " 
                UPDATE  producto
                SET producto = ?
                Where id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $producto);
            $stmt->bindValue(2, $id);
            $stmt->execute();
           
            return $this->redirect($this->generateUrl('producto_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a producto entity.
     *
     * @Route("/{id}", name="producto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
;
        if ($form->isValid()) {
            $sql = " 
                DELETE FROM producto
                WHERE id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
        }

        return $this->redirect($this->generateUrl('producto'));
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
            ->setAction($this->generateUrl('producto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar','attr' => ['class' => 'btn btn-danger']))
            ->getForm()
        ;
    }
}