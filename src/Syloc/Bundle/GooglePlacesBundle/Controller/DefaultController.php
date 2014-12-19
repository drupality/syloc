<?php

namespace Syloc\Bundle\GooglePlacesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GooglePlacesBundle:Default:index.html.twig', array('name' => $name));
    }
}
