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
	 * @Route("tweets", name="tweeter_query")
	 * 
	 */
	public function consultaTweetsAction(Request $request)
	{
		$form = $this->createForm(new ConsultaTweetsType($this->getDoctrine()->getManager()));
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
        $texto = $data['texto'];
        $mult = false;
      
      	$filter = [];
      	$filterUsuario = [];
      	$filterEst = [];
      	$filterImg = [];
      	$filterDate = [];
        $filterTexto = [];
      
      	if (isset($usuario)) {
	      	$filterUsuario = [
		      		'user.screen_name' 
		      			=> 	
		      		$this->getTwitterUsername($usuario)
	      		];
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
        if ($estadisticas == 1){
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
          $mult = true;
        }
        if (isset($texto)) {
          $filterTexto = [
              'text'=> array('$regex' => $texto)
            ];
        }
        //unir queries 
        $filter = array_merge($filter, $filterUsuario);
        $filter = array_merge($filter, $filterEst);
        $filter = array_merge($filter, $filterImg);
        $filter = array_merge($filter, $filterDate);
        $filter = array_merge($filter, $filterTexto);
        $query = new \MongoDB\Driver\Query($filter);
        //conectar con mongo
        $mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		$rows = $mongo->executeQuery('crm.tweets', $query);
		$tweets = [];
		foreach($rows as $r){
			$tweets[] = $r; 
  			
  			
		}
		
		 return $this->render(
                'MongoDBBundle:Default:consultaTweets.html.twig',
                [
                  'multimedia' => $mult,
                	'tweets' => $tweets,
                  'form' => $form->createView(),
                ]
            );
		

	}

	private function getTwitterUsername($usuario) {
		$sql = " 
            SELECT u.twitter_username
            FROM client u
            WHERE u.id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $usuario);
        $stmt->execute();
        $res = $stmt->fetchAll();
         return $res[0]["twitter_username"];
	}
}