<?php

namespace MongoDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use MongoDBBundle\Form\ConsultaEstadisticasTweetsType;
use Endroid\Twitter\Twitter;
/**
 * @Route("/estadisticas/")
 */
class EstadisticasTweetsController extends Controller
{
	/**
	 * @Route("tweets")
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function estadisticasTweetsAction(Request $request)
	{
		$form = $this->createForm(new ConsultaEstadisticasTweetsType());
		$form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'MongoDBBundle:Default:estadisticasHashtag.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $usuario = $data['usuario'];
        $fechaInicial = $data['fechaInicial'];
        $fechaFinal = $data['fechaFinal'];
        $hashtag = $data['hashtag'];
        $idioma = $data['idioma'];
        $fecha = $data['fecha'];
        $menciones = $data['menciones'];

        $filter = [];
      	$filterUsuario = [];
      	$filterDate = [];
        if (isset($usuario)) {
	      	$filterUsuario = [
		      		'user.id_str' 
		      			=> 	
		      		$this->getTwitterId($usuario)
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

      	//agrupar por hashtag
      	if ($hashtag == 1) {
      		$tweets = $this->queryTweetsHashTag($filter, $filterUsuario, $filterDate);
      		return $this->agruparPorHashtag($tweets, $form);
      	}
      	dump('errp');

	}
	private function queryTweetsHashTag($filter, $filterUsuario, $filterDate){
		$filter = array_merge($filter, $filterUsuario);
        $filter = array_merge($filter, $filterDate);

        $filterHashtag = 
    		[
    			'$where' => 
    			"this.entities.hashtags.length > 0"
    		];
        $filter = array_merge($filter, $filterHashtag);
        $query = new \MongoDB\Driver\Query($filter);
        //conectar con mongo
        $mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		$rows = $mongo->executeQuery('crm.tweets', $query);
		$tweets = [];
		foreach($rows as $r){
			$tweets[] = $r; 
  		}
		return $tweets;
	}

	private function agruparPorHashtag($tweets, $form){
		dump(count($tweets));
		$hashtags = [];
		//encontrar todos los hashtags únicos
		foreach ($tweets as $tweet) {
			$tags = $tweet->entities->hashtags;
			foreach($tags as $tag) {
				if (!in_array($tag->text, $hashtags)){
					$hashtags[] = $tag->text;
				}
			}

		}
		//ahora contar la cantidad que se repite cada hashtag
		$cantidadPorTag = [];
		foreach($hashtags as $hashtag) {
			$cant = 0;
			foreach ($tweets as $tweet) {
				$tags = $tweet->entities->hashtags;
				foreach($tags as $tag) {
					if ($hashtag == $tag->text){
						$cant = $cant + 1;
					}
				}

			}
			$cantidadPorTag[] = $cant;
		}
		dump(($hashtags));
		dump(($cantidadPorTag));
		return $this->render('MongoDBBundle:Default:estadisticasHashtag.html.twig',
			[
				'data' => true,
				'labels' => $hashtags,
				'cantidades' => $cantidadPorTag,
				'form' => $form->createView()
			]
		);

	}

	private function getTwitterId($usuario) {
		$sql = " 
            SELECT u.twitter_id
            FROM usuario u
            WHERE u.id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $usuario->getId());
        $stmt->execute();
        $res = $stmt->fetchAll();
         return $res[0]["twitter_id"];
	}
}