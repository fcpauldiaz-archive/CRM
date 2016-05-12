<?php

namespace Modulo1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Venta;
use Modulo1Bundle\Form\VentasType;
/**
 * Direccion controller.
 *
 * @Route("/ventas")
 */
class VentasController extends Controller
{
   /**
     * Lists all ventas entities.
     *
     * @Route("/", name="ventas")
     * @Method("GET")
     */
    public function indexAction()
    {
         $sql = " 
            SELECT  *
            FROM ventas
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Ventas:indexVentas.html.twig',
             array(
            'entities' => $res,
        ));
    }
     /**
     * Displays a form to create a new Ventas entity.
     *
     * @Route("/new", name="venta_new")
     * 
     * 
     */
    public function newAction(Request $request)
    {
        $entity = new Venta();
        $form   =  $this->createForm(new VentasType($this->getDoctrine()->getManager(),true));
        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('Modulo1Bundle:Ventas:newVenta.html.twig',
                 [
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ]
            );
        }
        $sql = " 
            INSERT INTO ventas
            VALUES (nextval('ventas_id_seq'), ?, ?,?,?)
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $form->getData()['producto']);
        $stmt->bindValue(2, $form->getData()['cantidad']);
        $stmt->bindValue(3, $form->getData()['total']);
        $stmt->bindValue(4, $form->getData()['cliente']);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $sql = " 
            Select currval('ventas_id_seq')
            FROM ventas
            LIMIT 1;
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->redirect($this->generateUrl('venta_show', array('id' => $res[0]["currval"])));
    }
    /**
     * Finds and displays a ventas entity.
     *
     * @Route("/{id}", name="venta_show")
     * @Method("GET")
     * @Template("Modulo1Bundle:Ventas:showVentas.html.twig")
     */
    public function showAction($id)
    {
         $sql = " 
            SELECT  *
            FROM ventas
            WHERE id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
       
          $sql = " 
            SELECT  producto
            FROM producto
            WHERE id = ?
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $res[0]["producto_id"]);
        $stmt->execute();
        $producto = $stmt->fetchAll()[0]["producto"];

          $sql = " 
            SELECT  nombres, apellidos
            FROM client
            WHERE id = ?
            ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $res[0]["client_id"]);
        $stmt->execute();
        $cliente = $stmt->fetchAll();
        $cliente = $cliente[0]["nombres"].' '.$cliente[0][
        "apellidos"];

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'producto' => $producto,
            'cliente' => $cliente,
            'entity'      => $res,
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Displays a form to edit an existing Ventas entity.
     *
     * @Route("/{id}/edit", name="venta_edit")
     * @Method("GET")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $sql = " 
            SELECT  *
            FROM ventas
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
        $entities = [];
        foreach($res as $entity){
            $ventas = new Venta();
            $ventas->setId($entity["id"]);
            $ventas->setProducto($entity["producto_id"]);
            $ventas->setCliente($entity["cliente_id"]);
            $ventas->setTotal($entity["total"]);
            $ventas->setCantidad($entity["cantidad"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $ventas;

        }
        $entity = $entities[0];
       

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Modulo1Bundle:Ventas:editVentas.html.twig', 
            [
              'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

            ]);
       
    }
    /**
    * Creates a form to edit a Ventas entity.
    *
    * @param Ventas $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ventas $entity)
    {
        $form = $this->createForm(new VentasType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('venta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => ['class' => 'btn btn-primary']));

        return $form;
    }
    /**
     * Edits an existing Correo entity.
     *
     * @Route("/{id}", name="venta_update")
     * @Method("PUT")
     * @Template("Modulo1Bundle:Ventas:editVentas.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $sql = " 
            SELECT  *
            FROM ventas
            Where id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $res = $stmt->fetchAll();
       $entities = [];
        foreach($res as $entity){
            $ventas = new Venta();
            $ventas->setId($entity["id"]);
            $ventas->setProducto($entity["producto_id"]);
            $ventas->setCliente($entity["cliente_id"]);
            $ventas->setTotal($entity["total"]);
            $ventas->setCantidad($entity["cantidad"]);
           // $correo->setCliente($entity["cliente_id"]);
            $entities[] = $ventas;

        }
        $entity = $entities[0];
       
       

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Correo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $data = $editForm->getData();
            $producto = $data['producto'];
            $cantidad = $data['cantidad'];
            $total = $data['total'];
            $cliente = $data['cliente'];
           
            $em = $this->getDoctrine()->getManager();
            $sql = " 
                UPDATE  ventas
                SET producto =?,cantidad = ?,total = ?, cliente = ?
                Where id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $producto);
            $stmt->bindValue(2, $cantidad);
            $stmt->bindValue(3, $total);
            $stmt->bindValue(4, $cliente);
            $stmt->bindValue(5, $id);
            $stmt->execute();
           
            return $this->redirect($this->generateUrl('venta_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ventas entity.
     *
     * @Route("/{id}", name="venta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
;
        if ($form->isValid()) {
            $sql = " 
                DELETE FROM ventas
                WHERE id = ?
                ";

            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
        }

        return $this->redirect($this->generateUrl('venta'));
    }
     /**
     * Creates a form to delete a Venta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('venta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar','attr' => ['class' => 'btn btn-danger']))
            ->getForm()
        ;
    }
}