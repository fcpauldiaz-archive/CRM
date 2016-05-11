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
        $cantidad = $data['cantidad'];
        $menciones = $data['menciones'];
        $retweet = $data['retweet'];

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
       if ($idioma == 1) {
       	$tweets = $this->queryAllTweets($filter, $filterUsuario, $filterDate);
       	return $this->agruparPorIdioma($tweets, $form);
       }

       if ($cantidad == 1) {
       		$tweets = $this->queryAllTweets($filter, $filterUsuario, $filterDate);
       		return $this->agruparCantidadTweets($tweets, $form);
       }

       if ($menciones == 1) {
       		$tweets = $this->queryAllTweets($filter, $filterUsuario, $filterDate);
       		return $this->agruparPorCantidadMenciones($tweets, $form);
       }
       if ($retweet == 1) {
       		$tweets = $this->queryRtTweets($filter, $filterUsuario, $filterDate);
       		return $this->agruparPorRt($tweets, $form);
       }

	}

	private function queryAllTweets($filter, $filterUsuario, $filterDate){
		$filter = array_merge($filter, $filterUsuario);
        $filter = array_merge($filter, $filterDate);

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

	private function queryRtTweets($filter, $filterUsuario, $filterDate)
	{
		$filter = array_merge($filter, $filterUsuario);
        $filter = array_merge($filter, $filterDate);
		$filterEst = [ 
        		'$or' => [
        			['retweet_count' => ['$gt' => 0]],
        			['favorite_count' => ['$gt' => 0]]
        		]
        		
        	];
        $filter = array_merge($filter, $filterEst);

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

	private function agruparPorRt($tweets, $form)
	{
		$fechas = [];
		foreach($tweets as $tweet) {
			$fechaActual = $tweet->created_at->toDateTime()->format('Y-m-d');;
			
			if (!in_array($fechaActual, $fechas)){
				$fechas[] = $fechaActual;
 			}
		}
		$cantRT = [];
		$cantFAV = [];
		foreach($fechas as $fecha) {
			$cantidadRT = 0;
			$cantidadFAV = 0;
			foreach($tweets as $tweet) {
				$fechaActual = $tweet->created_at->toDateTime()->format('Y-m-d');
				if ($fechaActual == $fecha){
					$cantidadRT +=  $tweet->retweet_count;
					$cantidadFAV += $tweet->favorite_count;
	 			}
			}
			$cantRT[] = $cantidadRT;
			$cantFAV[] = $cantidadFAV;
		}
		return $this->render('MongoDBBundle:Default:estadisticasRT.html.twig',
			[
				'data' => true,
				'labels' => $fechas,
				'RT' => $cantRT,
				'FAV' => $cantFAV,
				'form' => $form->createView()
			]
		);
	}

	private function agruparPorCantidadMenciones($tweets, $form)
	{
		$fechas = [];
		foreach($tweets as $tweet) {
			$fechaActual = $tweet->created_at->toDateTime()->format('Y-m-d');;
			
			
			if (!in_array($fechaActual, $fechas)){
				if (count($tweet->entities->user_mentions)>0){
					$fechas[] = $fechaActual;
				}
 			}
		}
		$cantPorTweet = [];
		foreach($fechas as $fecha) {
			$cantidadMentions = 0;
			foreach($tweets as $tweet) {
				$fechaActual = $tweet->created_at->toDateTime()->format('Y-m-d');
				if ($fechaActual == $fecha){
					$cantidadMentions += count($tweet->entities->user_mentions);
	 			}
			}
			$cantPorTweet[] = $cantidadMentions;
		}
		
		return $this->render('MongoDBBundle:Default:estadisticasCantidad.html.twig',
			[
				'data' => true,
				'labels' => $fechas,
				'cantidades' => $cantPorTweet,
				'form' => $form->createView()
			]
		);
	}
	private function agruparCantidadTweets($tweets, $form){
		$fechas = [];
		foreach($tweets as $tweet) {
			$fechaActual = $tweet->created_at->toDateTime()->format('Y-m-d');;
			
			if (!in_array($fechaActual, $fechas)){
				$fechas[] = $fechaActual;
 			}
		}
		$cantPorTweet = [];
		foreach($fechas as $fecha) {
			$cantTweets = 0;
			foreach($tweets as $tweet) {
				$fechaActual = $tweet->created_at->toDateTime()->format('Y-m-d');
				if ($fechaActual == $fecha){
					$cantTweets = $cantTweets + 1;
	 			}
			}
			$cantPorTweet[] = $cantTweets;
		}
		
		return $this->render('MongoDBBundle:Default:estadisticasCantidad.html.twig',
			[
				'data' => true,
				'labels' => $fechas,
				'cantidades' => $cantPorTweet,
				'form' => $form->createView()
			]
		);
		
	}

	private function agruparPorIdioma($tweets, $form) {
		$idiomas = [];
		foreach ($tweets as $tweet) {
			$lang = $tweet->lang;
			if (!in_array($lang, $idiomas)) {
				$idiomas[] = $lang;
			}

		}
		$cantIdiomas = [];
		$colors = [];
		foreach ($idiomas as $idioma) {
			$cantidadIdiomas = 0;
			foreach ($tweets as $tweet) {
				$lang = $tweet->lang;
				if ($lang == $idioma) {
					$cantidadIdiomas = $cantidadIdiomas + 1;
				}

			}
			$cantIdiomas[] = $cantidadIdiomas;
			$colors[] = '#'.$this->random_color();
		}

		return $this->render('MongoDBBundle:Default:estadisticasIdioma.html.twig',
			[
				'data' => true,
				'labels' => $idiomas,
				'cantidades' => $cantIdiomas,
				'colores' => $colors,
				'form' => $form->createView()
			]
		);
	}

	private function agruparPorHashtag($tweets, $form){
		
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
	private function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	private function random_color() {
	    return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}
}