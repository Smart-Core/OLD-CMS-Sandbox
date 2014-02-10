<?php

namespace SmartCore\Bundle\UnicatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UnicatBundle:Default:index.html.twig');
    }
}
