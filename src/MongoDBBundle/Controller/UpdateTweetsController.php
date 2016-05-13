<?php

namespace MongoDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Endroid\Twitter\Twitter;
use MongoDBBundle\Form\UpdateTweetsType;
/**
 * @Route("/update/tweets/")
 */
class UpdateTweetsController extends Controller
{
	/**
	 * @Route("usuario", name="update_usuario_tweets")
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function updateUserTweetsAction(Request $request)
	{
		$form = $this->createForm(new UpdateTweetsType());
		$form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'MongoDBBundle:Default:actualizarTweets.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $usuario = $data['usuario'];

		$bulk = new \MongoDB\Driver\BulkWrite(['ordered' => true]);
		$bulk->delete(['user.id_str' => $this->getTwitterId($usuario)]);

		$manager = new \MongoDB\Driver\Manager('mongodb://localhost:27017');
		//eliminar todos los tweets del usuario;
		$result = $manager->executeBulkWrite('crm.tweets', $bulk);

		$this->guardarNuevosTweets($usuario);


		return $this->redirectToRoute('homepage');


	}
        /**
     * @Route("cliente", name="update_client_tweets")
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateClientTweetsAction(Request $request)
    {
        $form = $this->createForm(new UpdateTweetsType($this->getDoctrine()->getManager()));
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'MongoDBBundle:Default:actualizarTweets.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $usuario = $data['cliente'];

        $bulk = new \MongoDB\Driver\BulkWrite(['ordered' => true]);
        $bulk->delete(['user.screen_name' => $this->getTwitterUsername($usuario)]);

        $manager = new \MongoDB\Driver\Manager('mongodb://localhost:27017');
        //eliminar todos los tweets del usuario;
        $result = $manager->executeBulkWrite('crm.tweets', $bulk);

        $this->guardarNuevosTweets($usuario);


        return $this->redirectToRoute('homepage');


    }

	private function guardarNuevosTweets($cliente) 
	{
        $usuario = $this->getUser();
        
		$twitter = new Twitter(
            "ADcfgE61LTgs6YU524t9yrU29", 
            "Z7oggnEwWq4mdOj0oapaH9rteMzURlZFb61IkxEe024tjQrMFU", 
            $this->getTwitterToken($usuario), 
            $this->getTitterSecretToken($usuario));
        // obtener tweets del usuario
        // Twitter api retorna máximo 199 por request
        // 3200 max en total
        $cantidadMax = 1500;
        $cantidadActual = 0;
        $max_id = '';
        $tweetsAcum = [];
        while ($cantidadActual < $cantidadMax) {
            if ($cantidadActual !=0 ){
                $tweets = $twitter->getTimeline(array(
                    'count' => 200,
                    'max_id' => $max_id,
                    'screen_name' => $this->getTwitterUsername($cliente)
                ));
            }else{
                 $tweets = $twitter->getTimeline(array(
                'count' => 200,
                'screen_name' => $this->getTwitterUsername($cliente)
            ));

            }
           
            $cont = 0;
            foreach ($tweets as $tweet) {
                if ($cantidadActual != 0){
                    if ($cont != 0){
                        $tweetsAcum[] = $tweet;
                    }
                }else{
                     $tweetsAcum[] = $tweet;
                }

                $cont = $cont + 1;
            }
            $cantidadActual = $cantidadActual + count($tweets);
           
            if (count($tweetsAcum) != 0) {
            	
                $max_id = $tweetsAcum[count($tweetsAcum)-1]->id_str;
            }
            else {
               // break;
            }

            

        } 

       $tweets = $tweetsAcum;
        //conectar con mongo
        $client = new \MongoDB\Client("mongodb://localhost:27017");

        $collection = $client->crm->tweets;
        foreach($tweets as &$tweet) {
            $date = new \DateTime($tweet->created_at);
            //$date = $date->format(\DateTime::ISO8601);
           
            $time = $date->getTimestamp();
            //$time = strval($time) + "000";
            $time = $time."000";
             
            $utcdatetime = new \MongoDB\BSON\UTCDateTime($time);
           
           
            $tweet->created_at = $utcdatetime;
           
        }
        
        //guardar los tweets
        $result = $collection->insertMany($tweets);
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
	private function getTwitterToken($usuario) {
		$sql = " 
            SELECT u.twitter_token
            FROM usuario u
            WHERE u.id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $usuario->getId());
        $stmt->execute();
        $res = $stmt->fetchAll();
       
         return $res[0]["twitter_token"];
	}
	private function getTitterSecretToken($usuario) {
		$sql = " 
            SELECT u.twitter_secret_token
            FROM usuario u
            WHERE u.id = ?
            ";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $usuario->getId());
        $stmt->execute();
        $res = $stmt->fetchAll();
       
         return $res[0]["twitter_secret_token"];
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