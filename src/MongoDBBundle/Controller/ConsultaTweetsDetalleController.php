<?php

namespace MongoDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Endroid\Twitter\Twitter;

/**
 * @Route("/consulta/resumen")
 */
class ConsultaTweetsDetalleController extends Controller
{
	/**
	 * @Route("tweets", name="tweeter_query_resumen")
	 * 
	 */
	public function showStatsAction(Request $request)
	{
		//usuario con más menciones
		$usuario = $this->showClient();
		
		//cantidad de tweets promedio
		$cantidadTweets = $this->promTweets();
		
		//Usuario con mayor cantidad de tweets.
		$usuarioTweets = $this->usuarioTweets();
		
		//Usuario con mayor cantidad de followers.
		$usuarioFollowers = $this->usuarioFollowers();
		
		//Usuario con mayor relación followers/following.
		$usuarioRelacion = $this->usuarioFollowerRel();
		

		return $this->render('MongoDBBundle:Default:estadisticasResumen.html.twig',[
				'usuarioMenciones' => $usuario,
				'cantidadTweets' => $cantidadTweets,
				'usuarioTweets' => $usuarioTweets,
				'usuarioFollowers' => $usuarioFollowers,
				'usuarioRelacion' => $usuarioRelacion
			]);

	
	}

	private function usuarioFollowerRel()
	{
		$query = new \MongoDB\Driver\Query([]);
        //conectar con mongo
        $mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		$rows = $mongo->executeQuery('crm.tweets', $query);
		$tweets = [];
		foreach($rows as $r){
			$tweets[] = $r; 
		}

		$usuarios = [];
		$usuarioTotal = "";
		$cantidad = 0;
		foreach($tweets as $tweet){
			if (!in_array($tweet->user->id_str, $usuarios)){
				$usuarios[] = $tweet->user->id_str;
				$cant = $tweet->user->followers_count;
				$cant2 = $tweet->user->friends_count;
				$rel = $cant/$cant2;
				if ($rel > $cantidad){
					$usuarioTotal = $tweet->user->screen_name;
					$cantidad = $rel;
				}
			}
		}
		return [$usuarioTotal, $cantidad];
	}

	private function usuarioFollowers()
	{
		$query = new \MongoDB\Driver\Query([]);
        //conectar con mongo
        $mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		$rows = $mongo->executeQuery('crm.tweets', $query);
		$tweets = [];
		foreach($rows as $r){
			$tweets[] = $r; 
		}

		$usuarios = [];
		$usuarioTotal = "";
		$cantidad = 0;
		foreach($tweets as $tweet){
			if (!in_array($tweet->user->id_str, $usuarios)){
				$usuarios[] = $tweet->user->id_str;
				$cant = $tweet->user->followers_count;
				if ($cant > $cantidad){
					$usuarioTotal = $tweet->user->screen_name;
					$cantidad = $cant;
				}
			}
		}
		return [$usuarioTotal, $cantidad];
	}

	private function usuarioTweets(){

		$query = new \MongoDB\Driver\Query([]);
        //conectar con mongo
        $mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		$rows = $mongo->executeQuery('crm.tweets', $query);
		$tweets = [];
		foreach($rows as $r){
			$tweets[] = $r; 
		}
		$usuarios = [];
		$usuarioTotal = "";
		$cantidad = 0;
		foreach($tweets as $tweet){
			if (!in_array($tweet->user->id_str, $usuarios)){
				$usuarios[] = $tweet->user->id_str;
				$cant = $tweet->user->statuses_count;
				if ($cant > $cantidad){
					$usuarioTotal = $tweet->user->screen_name;
					$cantidad = $cant;
				}
			}
		}
		return [$usuarioTotal, $cantidad];

	}


	private function promTweets()
	{
		
		
    	$query = new \MongoDB\Driver\Query([]);
        //conectar con mongo
        $mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		$rows = $mongo->executeQuery('crm.tweets', $query);
		$tweets = [];
		foreach($rows as $r){
			$tweets[] = $r; 
		}
		$usuarios = [];
		$cantidad = [];
		foreach($tweets as $tweet){
			if (!in_array($tweet->user->id_str, $usuarios)){
				$usuarios[] = $tweet->user->id_str;
				$cantidad[] = $tweet->user->statuses_count;
			}
		}
		$prom = array_sum($cantidad)/count($cantidad);

		return $prom;
	}

	private function showClient(){
		
		$filterMenciones = 
    		[
    			'$where' => 
    			"this.entities.user_mentions.length > 0"
    		];
    	$query = new \MongoDB\Driver\Query($filterMenciones);
        //conectar con mongo
        $mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
		$rows = $mongo->executeQuery('crm.tweets', $query);
		$tweets = [];
		foreach($rows as $r){
			$tweets[] = $r; 
		}
		$screen_name="";
		$name= "";
		$id_str="";
		$cantidadTotal = 0;
		foreach($tweets as $tweet) {
			foreach($tweet->entities->user_mentions as $mention) {
				$id_str = $mention->id_str;
				$cantidad = 0;
				foreach($tweets as $tweet2) {
					foreach($tweet2->entities->user_mentions as $mention2) {
						if ($mention2->id_str == $id_str){
							$cantidad++;
						}

					}
				}
				if($cantidad > $cantidadTotal){
					$screen_name = $mention->screen_name;
					$name = $mention->name;
					$cantidadTotal = $cantidad;
				}
			}
		}

		
		return [$screen_name, $name, $cantidadTotal];

	}

}