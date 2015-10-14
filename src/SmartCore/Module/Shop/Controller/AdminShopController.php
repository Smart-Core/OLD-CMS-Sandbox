<?php

namespace SmartCore\Module\Shop\Controller;

use Knp\RadBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Module\Shop\Entity\Order;
use Symfony\Component\HttpFoundation\Request;

class AdminShopController extends Controller
{
    public function indexAction(Request $request)
    {

        return $this->render('ShopModule:Admin:index.html.twig', []);
    }


    public function ordersAction()
    {
        return $this->redirectToRoute('smart_module.shop.admin.orders.active');
    }


    public function basketAction(Order $order)
    {
        return $this->render('ShopModule:Admin:basket.html.twig', [
            'order' => $order,
        ]);
    }


    public function orderAction(Order $order)
    {
        return $this->render('ShopModule:Admin:order_show.html.twig', [
            'order' => $order,
        ]);
    }


    public function ordersAllAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModule:Order')->findAllVisible();

        return $this->render('ShopModule:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersActiveAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModule:Order')->findActive();

        return $this->render('ShopModule:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersCompletedAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModule:Order')->findBy(['status' => Order::STATUS_FINISHED], ['id' => 'DESC']);

        return $this->render('ShopModule:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersCancelledAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModule:Order')->findBy(['status' => Order::STATUS_CANCELLED], ['id' => 'DESC']);

        return $this->render('ShopModule:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersShippingAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModule:Order')->findBy(['status' => Order::STATUS_SHIPPING], ['id' => 'DESC']);

        return $this->render('ShopModule:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function basketsAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModule:Order')->findBy(['status' => Order::STATUS_PENDING], ['id' => 'DESC']);

        return $this->render('ShopModule:Admin:baskets.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function settingsAction()
    {
        return $this->render('ShopModule:Admin:settings.html.twig', []);
    }

    public function shippingAction()
    {
        return $this->redirectToRoute('smart_module.shop.admin.shipping.settings');
    }


    public function shippingActiveAction()
    {
        return $this->render('ShopModule:Admin:shipping.html.twig', []);
    }


    public function shippingFinishedAction()
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
