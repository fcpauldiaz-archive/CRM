<?php

namespace MongoDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("show/tweets", name="show_tweets")
     * 
     */
    public function showTweetsAction()
    {
        
        $twitter = $this->get('endroid.twitter');

        // Retrieve the user's timeline
        $tweets = $twitter->getTimeline(array(
            'count' => 500,
            'screen_name' => 'kevinest2g',
        ));
       

        // 
        // replace this example code with whatever you need
        /*$usuario = $this->getDoctrine()->getRepository('UserBundle:Usuario')->findOneById(1);*/
       
        return $this->render('MongoDBBundle:Default:showTweets.html.twig', array(
            'tweets' => $tweets,

        ));
    }
}
