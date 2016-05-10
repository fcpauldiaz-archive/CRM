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
        //$client = new \MongoDB\Client("mongodb://localhost:27017");
        //$collection = $client->demo->beers;

        //$result = $collection->insertOne( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );
         

       // $mongo = $connection->getMongoClient();xxx
        return $this->render('default/index.html.twig');
       
    }
}
