<?php

namespace SmartCore\Bundle\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $mc = $this->get('smart_media')->getCollection(1);

        $files = [
            $mc->get(1),
            $mc->get(2),
        ];

        return $this->render('SmartMediaBundle:Default:index.html.twig', [
            'files' => $files,
        ]);
    }
}
