<?php

namespace MongoDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MongoDBBundle\Form\CantidadTweetsType;
use Endroid\Twitter\Twitter;


class DefaultController extends Controller
{
    /**
     * @Route("show/tweets", name="show_tweets")
     * 
     */
    public function showTweetsAction()
    {
        
        /*$twitter = $this->get('endroid.twitter');

        // Retrieve the user's timeline
        $tweets = $twitter->getTimeline(array(
            'count' => 500,
            'max_id' => 586242080827146240,
        ));
        $client = new \MongoDB\Client("mongodb://localhost:27017");
        $collection = $client->crm->tweets;

       // $result = $collection->insertMany($tweets);
        // replace this example code with whatever you need*/
        $usuario = $this->getDoctrine()->getRepository('UserBundle:Usuario')->findOneById(1);
        $twitter = new Twitter(
        	"ADcfgE61LTgs6YU524t9yrU29", 
        	"Z7oggnEwWq4mdOj0oapaH9rteMzURlZFb61IkxEe024tjQrMFU", 
        	$usuario->getTwitterToken(), 
        	$usuario->getTokenScret());
      	// Retrieve the user's timeline
		$tweets = $twitter->getTimeline(array(
		    'count' => 5
		));
        return $this->render('MongoDBBundle:Default:showTweets.html.twig', array(
            'array_tokens' => 
            [
            		$usuario->getTwitterToken(),
            		$usuario->getTokenScret(),
            		"163336024-QxifqxVCZabZEF5AUCeaIQuGvAScfoQKz9MfQB5q",
            		"ahkyVCn3l555jCH0joxhZuPikW5zHT6eyZ6Qq4043GkNz"

            ],
            'tweets' => $tweets,

        ));
    }
}
