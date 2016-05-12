<?php

namespace Modulo1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Modulo1Bundle\Form\ConsultaSQLEstadisticaType;
/**
 * Direccion controller.
 *
 * @Route("/estadisticas")
 */
class Modulo1EstadisticasController extends Controller
{
	/**
	 * @Route("/sql", name="estadisticas_sql")
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 * @Template("Modulo1Bundle:Consulta:estadisticasSQL.html.twig")
	 */
	public function estadisticasAction(Request $request)
	{ 
		$form = $this->createForm(new ConsultaSQLEstadisticaType( $this->getDoctrine()->getManager()));

		$form->handleRequest($request);
        if (!$form->isValid()) {
            return 
            [
                'form' => $form->createView(),
            ];
            
        }
        $data = $form->getData();
        $fechaInicial = $data['fechaInicial'];
        $fechaFinal = $data['fechaFinal'];
        $ventasFecha = $data['ventas_fecha'];
        $clientesFecha = $data['clientes_fecha'];
        $ventasTotales = $data['ventas_totales'];
        $productos = $data['productos_vendidos'];
        $producto_total = $data['producto_total_fecha'];


        if ($ventasFecha == 1){

	       
	        $sqlVentasFecha =
	        "
	        	Select * 
	        	from ventas_fecha(?, ?);
	        ";

	        $em = $this->getDoctrine()->getManager();
	        $stmt = $em->getConnection()->prepare($sqlVentasFecha);
	        $stmt->bindValue(1, $fechaInicial, 'datetime');
	        $stmt->bindValue(2, $fechaFinal, 'datetime');
	        $stmt->execute();
	        $res = $stmt->fetchAll();
	        $fechas = [];
	        $cantidades = [];
	      	foreach ($res as $r){
	      		$fechas[] = $r["f"];
	      		$cantidades[] = $r["cant"];
	      	}

	      	return $this->render('Modulo1Bundle:Consulta:graficaVentas.html.twig',[
	      		'data' => true,
	      		'labels' => $fechas,
	      		'cantidades'=> $cantidades,
	      		'form' => $form->createView()
	      		]);
      	
    	}
    	if ($clientesFecha == 1){
    		  $sqlVentasFecha =
		        "
		        	Select * 
		        	from clientes_fecha(?, ?);
		        ";

		        $em = $this->getDoctrine()->getManager();
		        $stmt = $em->getConnection()->prepare($sqlVentasFecha);
		        $stmt->bindValue(1, $fechaInicial, 'datetime');
		        $stmt->bindValue(2, $fechaFinal, 'datetime');
		        $stmt->execute();
		        $res = $stmt->fetchAll();
		        $cantidades = [];
		      	foreach ($res as $r){
		      		$fechas[] = $r["f"];
		      		$cantidades[] = $r["cant"];
		      	}

		      	return $this->render('Modulo1Bundle:Consulta:graficaClientes.html.twig',[
		      		'data' => true,
		      		'labels' => $fechas,
		      		'cantidades'=> $cantidades,
		      		'form' => $form->createView()
		      		]);
    	}
    	if ($ventasTotales == 1){

    		 	$sqlVentasFecha =
		        "
		        	Select * 
		        	from ventas_totales_fecha(?, ?);
		        ";

		        $em = $this->getDoctrine()->getManager();
		        $stmt = $em->getConnection()->prepare($sqlVentasFecha);
		        $stmt->bindValue(1, $fechaInicial, 'datetime');
		        $stmt->bindValue(2, $fechaFinal, 'datetime');
		        $stmt->execute();
		        $res = $stmt->fetchAll();
		       	foreach ($res as $r){
		      		$fechas[] = $r["f"];
		      		$cantidades[] = $r["cant"];
		      	}

		      	return $this->render('Modulo1Bundle:Consulta:graficaVTotal.html.twig',[
		      		'data' => true,
		      		'labels' => $fechas,
		      		'cantidades'=> $cantidades,
		      		'form' => $form->createView()
		      		]);


    	}
    	if ($productos == 1){
    		

    		 $sqlVentasFecha =
		        "
		        	Select * 
		        	from producto_fecha(?, ?);
		        ";

		        $em = $this->getDoctrine()->getManager();
		        $stmt = $em->getConnection()->prepare($sqlVentasFecha);
		        $stmt->bindValue(1, $fechaInicial, 'datetime');
		        $stmt->bindValue(2, $fechaFinal, 'datetime');
		        $stmt->execute();
		        $res = $stmt->fetchAll();
		        foreach ($res as $r){
		      		$fechas[] = $r["f"];
		      		$cantidades[] = $r["cant"];
		      	}

		      	return $this->render('Modulo1Bundle:Consulta:graficaProductosFecha.html.twig',[
		      		'data' => true,
		      		'labels' => $fechas,
		      		'cantidades'=> $cantidades,
		      		'form' => $form->createView()
		      		]);
    	}
    	if ($producto_total == 1) {
    		 $sqlVentasFecha =
		        "
		        	Select * 
		        	from producto_totales (?, ?);
		        ";

		        $em = $this->getDoctrine()->getManager();
		        $stmt = $em->getConnection()->prepare($sqlVentasFecha);
		        $stmt->bindValue(1, $fechaInicial, 'datetime');
		        $stmt->bindValue(2, $fechaFinal, 'datetime');
		        $stmt->execute();
		        $res = $stmt->fetchAll();
		        
		        foreach ($res as $r){
		      		$fechas[] = $r["f"].' : '.$r["producto"];
		      		$cantidades[] = $r["cant"];
		      	}

		     

		      	return $this->render('Modulo1Bundle:Consulta:graficaProductoTotal.html.twig',[
		      		'data' => true,
		      		'labels' => $fechas,
		      		'cantidades'=> $cantidades,
		      		'form' => $form->createView()
		      		]);
    	}




		
	}

}