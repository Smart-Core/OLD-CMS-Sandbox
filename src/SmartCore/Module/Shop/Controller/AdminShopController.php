<?php

namespace SmartCore\Module\Shop\Controller;

use Knp\RadBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

class AdminShopController extends Controller
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

    public function shippingActiveAction(Request $request)
    {
        return $this->render('ShopModule:Admin:shipping.html.twig', []);
    }

    public function shippingFinishedAction(Request $request)
    {
        return $this->render('ShopModule:Admin:shipping.html.twig', []);
    }

    public function shippingSettingsAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($em->getRepository('ShopModule:Shipping')->getFindByQuery([])));
        $pagerfanta->setMaxPerPage(10);

        try {
            $pagerfanta->setCurrentPage($request->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_module.shop.admin.shipping.settings'));
        }

        return $this->render('ShopModule:Admin:shipping_settings.html.twig', [
            'pagerfanta' => $pagerfanta,
        ]);
    }

}
