<?php

namespace SmartCore\Bundle\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $collection = $this->get('smart_media_collection');

        $files = [
            $collection->getUriByFileId(1),
            $collection->getUriByFileId(2),
        ];

        return $this->render('SmartMediaBundle:Default:index.html.twig', [
            'files' => $files,
        ]);
    }
}
