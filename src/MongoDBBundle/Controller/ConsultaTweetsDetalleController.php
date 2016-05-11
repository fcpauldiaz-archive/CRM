<?php

namespace MongoDBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Endroid\Twitter\Twitter;
/**
 * @Route("/consulta/detalle")
 */
class ConsultaTweetsDetalleController extends Controller
{
	/**
	 * @Route("tweets", name="tweeter_query_detalle")
	 * 
	 */
	public function showStatsAction(Request $request)
	{
	
	}

}