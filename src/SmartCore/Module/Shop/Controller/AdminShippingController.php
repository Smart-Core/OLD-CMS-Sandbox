<?php

namespace SmartCore\Module\Shop\Controller;

use Smart\CoreBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminShippingController extends Controller
{
    public function indexAction(Request $request)
    {

        return $this->render('ShopModule:Admin:index.html.twig', []);
    }

    public function settingsAction(Request $request)
    {
        return $this->render('ShopModule:Admin:settings.html.twig', []);
    }

    public function shippingAction(Request $request)
    {
        return $this->render('ShopModule:Admin:shipping.html.twig', []);
    }

   public function shippingSettingsAction(Request $request)
    {
        return $this->render('ShopModule:Admin:shipping_settings.html.twig', []);
    }

}
