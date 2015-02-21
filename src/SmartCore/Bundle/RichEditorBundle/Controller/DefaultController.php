<?php

namespace SmartCore\Bundle\RichEditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SmartRichEditorBundle:Default:index.html.twig', array('name' => $name));
    }
}
