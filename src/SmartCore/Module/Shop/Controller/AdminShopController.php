<?php

namespace SmartCore\Module\Shop\Controller;

use Knp\RadBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminShopController extends Controller
{
    public function indexAction(Request $request)
    {

        return $this->render('ShopModule:Admin:index.html.twig', []);
    }
}
