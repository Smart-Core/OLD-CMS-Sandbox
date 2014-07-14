<?php

namespace SmartCore\Module\Gallery\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;

class GalleryController extends Controller
{
    use NodeTrait;

    /**
     * @var int
     */
    protected $gallery_id;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $albums = [];

        return $this->render('GalleryModule::index.html.twig', [
            'albums'  => $albums,
        ]);
    }
}
