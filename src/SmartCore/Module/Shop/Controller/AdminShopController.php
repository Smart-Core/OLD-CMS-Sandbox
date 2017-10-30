<?php

namespace SmartCore\Module\Shop\Controller;

use Smart\CoreBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Module\Shop\Entity\Order;
use Symfony\Component\HttpFoundation\Request;

class AdminShopController extends Controller
{
    public function indexAction(Request $request)
    {

        return $this->render('ShopModuleBundle:Admin:index.html.twig', []);
    }


    public function ordersAction()
    {
        return $this->redirectToRoute('smart_module.shop.admin.orders.active');
    }


    public function basketAction(Order $order)
    {
        return $this->render('ShopModuleBundle:Admin:basket.html.twig', [
            'order' => $order,
        ]);
    }


    public function orderAction(Order $order)
    {
        return $this->render('ShopModuleBundle:Admin:order_show.html.twig', [
            'order' => $order,
        ]);
    }


    public function ordersAllAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModuleBundle:Order')->findAllVisible();

        return $this->render('ShopModuleBundle:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersActiveAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModuleBundle:Order')->findActive();

        return $this->render('ShopModuleBundle:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersCompletedAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModuleBundle:Order')->findBy(['status' => Order::STATUS_FINISHED], ['id' => 'DESC']);

        return $this->render('ShopModuleBundle:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersCancelledAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModuleBundle:Order')->findBy(['status' => Order::STATUS_CANCELLED], ['id' => 'DESC']);

        return $this->render('ShopModuleBundle:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function ordersShippingAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModuleBundle:Order')->findBy(['status' => Order::STATUS_SHIPPING], ['id' => 'DESC']);

        return $this->render('ShopModuleBundle:Admin:orders_list.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function basketsAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        // @todo пагинацию
        $orders = $em->getRepository('ShopModuleBundle:Order')->findBy(['status' => Order::STATUS_PENDING], ['id' => 'DESC']);

        return $this->render('ShopModuleBundle:Admin:baskets.html.twig', [
            'pagerfanta' => $orders,
        ]);
    }


    public function settingsAction()
    {
        return $this->render('ShopModuleBundle:Admin:settings.html.twig', []);
    }

    public function shippingAction()
    {
        return $this->redirectToRoute('smart_module.shop.admin.shipping.settings');
    }


    public function shippingActiveAction()
    {
        return $this->render('ShopModuleBundle:Admin:shipping.html.twig', []);
    }


    public function shippingFinishedAction()
    {
        return $this->render('ShopModuleBundle:Admin:shipping.html.twig', []);
    }


    public function shippingSettingsAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($em->getRepository('ShopModuleBundle:Shipping')->getFindByQuery([])));
        $pagerfanta->setMaxPerPage(10);

        try {
            $pagerfanta->setCurrentPage($request->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_module.shop.admin.shipping.settings'));
        }

        return $this->render('ShopModuleBundle:Admin:shipping_settings.html.twig', [
            'pagerfanta' => $pagerfanta,
        ]);
    }

}
