<?php

namespace Chema\ArsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
    	return [];
    }

    /**
     * @Route("/load-json")
     * @Template()
     */
    public function loadJsonAction()
    {

    	return $this->render('ChemaArsBundle:Main:loadJson.html.twig');
    }
}
