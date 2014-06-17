<?php

namespace SmartCore\Bundle\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SmartChatBundle:Default:index.html.twig', array('name' => $name));
    }
}
