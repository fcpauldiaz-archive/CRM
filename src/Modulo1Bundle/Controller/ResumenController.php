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

        /**
     * Lists all direccion entities.
     *
     * @Route("/cliente_membresia", name="cliente_membresia_resumen")
     * 
     */
    public function detalleAction(Request $request)
    {
         $sql = " 
           select * from cliente_membresia WHERE tipo_membresia = ?
              ORDER BY cliente_membresia.id
            ";
        $form = $this
            ->get('form.factory')
            ->createNamedBuilder('', 'form', [], ['csrf_protection' => false])
            ->add('membresia', 'choice', [
                'choices' => [
                    'oro' => 'oro',
                    'plata' =>'plata',
                    'bronce' => 'bronce'
                ],
                'required' => true,
            ])
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('Modulo1Bundle:Resumen:clienteMembresiaResumen.html.twig',
             array(
            'cliente_membresia' => [],
            'form' => $form->createView(),
        ));
        }
        $mem = $form->getData()['membresia'];
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $mem);
        $stmt->execute();
        $cliente_membresia = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Resumen:clienteMembresiaResumen.html.twig',
             array(
            'cliente_membresia' => $cliente_membresia,
            'form' => $form->createView()
        ));

    }
        /**
     * Lists all direccion entities.
     *
     * @Route("/venta_producto", name="venta_producto_resumen")
     * 
     */
    public function detalleProductoAction(Request $request)
    {
         $sql = " 
           select * from venta_producto 
           Where producto = ?
           and fecha >= ?
           and fecha <= ?
           ";
           $form = $this
            ->get('form.factory')
            ->createNamedBuilder('', 'form', [], ['csrf_protection' => false])
            ->add('fechaInicio', 'date', [
                'required' => true,
            ])
             ->add('fechaFinal', 'date', [
                'required' => true,
            ])
            ->add('producto')
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if (!$form->isValid()){
             return $this->render('Modulo1Bundle:Resumen:ventaProductoResumen.html.twig',
             array(
            'venta_producto' =>[],
            'form' => $form->createView()
        ));
        }
        $data = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $data['producto']);
        $stmt->bindValue(2, $data['fechaInicio'], 'datetime');
        $stmt->bindValue(3, $data['fechaFinal'], 'datetime');

        $stmt->execute();
        $venta_producto = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Resumen:ventaProductoResumen.html.twig',
             array(
            'venta_producto' => $venta_producto,
             'form' => $form->createView()
        ));

    }
    /**
     * @Route("/search/cliente", name="search_cliente")
     */
    public function SearchClienteAction(Request $request)
    {
         $sql = " 
           select * from client 
           WHERE id is not null
           ";
           $form = $this
            ->get('form.factory')
            ->createNamedBuilder('', 'form', [], ['csrf_protection' => false])
            ->add('fecha_nacimiento', 'date', [
                'required' => false,
            ])
            ->add('estado_civil', 'text', [
                'required' => false,
            ])
            ->add('frecuente', 'checkbox', [
                'required' => false,
            ])
            ->add('profesion', 'text', [
                'required' => false,
            ])
            ->add('nacionalidad', 'text', [
                'required' => false,
            ])
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if (!$form->isValid()){
            return $this->render('Modulo1Bundle:Resumen:searchClientes.html.twig',[
                'form' => $form->createView(),
                'clientes' => [],
                ]);

        }
        $data = $form->getData();
        $em = $this->getDoctrine()->getManager();
       
        if (isset($data['fecha_nacimiento'])){
                    $sql.="and fecha_nacimiento = ?";    
        }
        if (isset($data['estado_civil'])){
            $sql.="and estado_civil = ?";
        }
        if (isset($data['frecuente'])){
            $sql.="and frecuente = ?";
        }
        if (isset($data['profesion'])){
            $sql.="and profesion = ?";
        }
        if (isset($data['nacionalidad'])){
            $sql.="and nacionalidad = ?";
        }

        $stmt = $em->getConnection()->prepare($sql);
       
        $cont = 1;
        if (isset($data['fecha_nacimiento'])){ 
            $stmt->bindValue($cont, $data['fecha_nacimiento']); 
            $cont++;
        }
        if (isset($data['estado_civil'])){
            $stmt->bindValue($cont, $data['estado_civil']); 
            $cont++;
        }
        if (isset($data['frecuente'])){
            $stmt->bindValue($cont, $data['frecuente'],  \PDO::PARAM_BOOL); 
            $cont++;
        }
        if (isset($data['profesion'])){
            $stmt->bindValue($cont, $data['profesion']); 
            $cont++;
        }
        if (isset($data['nacionalidad'])){
             $stmt->bindValue($cont, $data['nacionalidad']); 
            $cont++;
        }
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $this->render('Modulo1Bundle:Resumen:searchClientes.html.twig',[
                'form' => $form->createView(),
                'clientes' => $res,
             ]);


    }

}