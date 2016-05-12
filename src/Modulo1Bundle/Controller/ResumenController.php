<?php

namespace Modulo1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * Direccion controller.
 *
 * @Route("/resumen")
 */
class ResumenController extends Controller
{
   /**
     * Lists all direccion entities.
     *
     * @Route("/", name="resumen")
     * @Method("GET")
     */
    public function indexAction()
    {
         $sql = " 
           select * from ventas_mayor_a_100
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $ventas_mayor = $stmt->fetchAll();
         $sql = " 
           select * from top5_producto
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $top5_producto = $stmt->fetchAll();
        $sql = " 
           select * from top_cliente
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $top_cliente = $stmt->fetchAll();
        $sql = " 
           select * from compra_cliente_membresia
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $compra_cliente_membresia = $stmt->fetchAll();
        $sql = " 
           select * from total_por_cliente
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $total_por_cliente = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Resumen:indexResumen.html.twig',
             array(
            'ventas_mayor' => $ventas_mayor,
            'top5'=>$top5_producto,
            'top_cliente'=>$top_cliente,
            'compra_cliente_membresia'=>$compra_cliente_membresia,
            'total_por_cliente'=>$total_por_cliente,

        ));

    }
}