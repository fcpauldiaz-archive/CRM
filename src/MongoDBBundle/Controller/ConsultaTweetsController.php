<?php

namespace MongoDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use MongoDBBundle\Form\ConsultaTweetsType;
use Endroid\Twitter\Twitter;
/**
 * @Route("/consulta/")
 */
class ConsultaTweetsController extends Controller
{
	/**
	 * @Route("tweets")
	 * 
	 */
	public function consultaTweetsAction(Request $request)
	{
		$form = $this->createForm(new ConsultaTweetsType());
		$form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'MongoDBBundle:Default:consultaTweets.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $usuario = $data['usuario'];
        $fechaInicial = $data['fechaInicial'];
        $fechaFinal = $data['fechaFinal'];
        $imagen = $data['imagen'];
        $estadisticas = $data['estadisticas'];
         //conectar con mongo
      	$mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
      	$filter = [];
      	$filterUsuario = [];
      	$filterEst = [];
      	$filterImg = [];
      	$filterDate = [];
      	if (isset($usuario)) {
      		$filterUsuario = ['user.id_str' => $usuario->getTwitterId()];
      	}
      	if (isset($fechaInicial) && isset($fechaFinal)) {
      	
			$time1 = strtotime($fechaInicial->format('Y-m-d'));
			$time2 = strtotime($fechaFinal->format('Y-m-d'));
			$time1 = $time1.'000';
			$time2 = $time2.'000';
         	$utcFirst = new \MongoDB\BSON\UTCDateTime($time1);
			$utcSecond = new \MongoDB\BSON\UTCDateTime($time2);
			$filterDate = [
				'created_at' => 
					[
						
						'$gte' => $utcFirst,
						'$lte' => $utcSecond
						
						
					]
			];


      	}
        if ($estadisticas == 0){
        	$filterEst = [ 
        		'$or' => [
        			['retweet_count' => ['$gt' => 0]],
        			['favorite_count' => ['$gt' => 0]]
        		]
        		
        	];
        }
        if ($imagen == 1 ){
        	$filterImg = [
        		'entities.media' => [
        			'$exists' => true
        		]
        	];
        }
        $filter = array_merge($filter, $filterUsuario);
        //$filter = array_merge($filter, $filterEst);
        //$filter = array_merge($filter, $filterImg);
        $filter = array_merge($filter, $filterDate);
        $query = new \MongoDB\Driver\Query($filter);
		$rows = $mongo->executeQuery('crm.tweets', $query);

		
		$cantidad = 0;
		foreach($rows as $r){
  			dump($r);
  			$cantidad++;
		}
		
		/* $client = new \MongoDB\Client("mongodb://localhost:27017");

        $collection = $client->crm->tweets;
        //guardar los tweets
        $rows = $collection->find(['user.id_str' => $usuario->getTwitterId()]);
       foreach($rows as $r){
       		$json = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($r));
  			dump($json);
		}*/
		

	}
}