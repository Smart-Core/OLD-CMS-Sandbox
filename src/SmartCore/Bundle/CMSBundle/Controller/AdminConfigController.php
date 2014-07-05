<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Knp\RadBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminConfigController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('CMSBundle:AdminConfig:index.html.twig', [

        ]);
    }
}
