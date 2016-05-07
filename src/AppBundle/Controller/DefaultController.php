<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //$dm = $this->get('doctrine_mongodb')->getManager();
      $client = new \MongoDB\Client("mongodb://localhost:27017");
        $collection = $client->demo->beers;

        $result = $collection->insertOne( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );
     

       // $mongo = $connection->getMongoClient();

       
        $twitter = $this->get('endroid.twitter');

        // Retrieve the user's timeline
        $tweets = $twitter->getTimeline(array(
            'count' => 5
        ));
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'tweets' => $tweets,
        ));
    }
}
