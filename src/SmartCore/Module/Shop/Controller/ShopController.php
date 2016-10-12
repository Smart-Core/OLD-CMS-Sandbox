<?php

namespace SmartCore\Module\Shop\Controller;

use Smart\CoreBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Model\UserModel;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Shop\Entity\Order;
use SmartCore\Module\Shop\Entity\OrderItem;
use SmartCore\Module\Shop\Form\Type\OrderConfirmFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ShopController extends Controller
{
    use NodeTrait;

    protected $mode;
    protected $basket_node_id = 36; // @todo глобальную настройку ноды корзинки

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        switch ($this->mode) {
            case 'basket_widget':
                return $this->basketWidgetAction();
            case 'basket':
                return $this->myBasketAction();
            case 'my_orders':
                return $this->redirectToRoute('smart_module.shop.orders.active');
        }

        return $this->render('ShopModule::index.html.twig', []);
    }

    public function ordersAllAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $orders = $em->getRepository('ShopModule:Order')->findAllVisible($this->getUser());

        return $this->render('ShopModule::orders.html.twig', [
            'orders' => $orders,
        ]);
    }
    
    public function ordersActiveAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $orders = $em->getRepository('ShopModule:Order')->findActive($this->getUser());

        return $this->render('ShopModule::orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    public function ordersCompletedAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $orders = $em->getRepository('ShopModule:Order')->findBy(['status' => Order::STATUS_FINISHED, 'user' => $this->getUser()], ['id' => 'DESC']);

        return $this->render('ShopModule::orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    public function ordersCancelledAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $orders = $em->getRepository('ShopModule:Order')->findBy(['status' => Order::STATUS_CANCELLED, 'user' => $this->getUser()], ['id' => 'DESC']);

        return $this->render('ShopModule::orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $order = $em->getRepository('ShopModule:Order')->findOneBy(['status' => Order::STATUS_PENDING, 'user' => $this->getUser()], ['id' => 'DESC']);

        if (empty($order)) {
            return $this->redirectToRoute('smart_module.shop.index');
        }

        $items = [];

        $ucm = $this->get('unicat')->getConfigurationManager($this->get('settings')->get('shopmodule:catalog'));

        foreach ($order->getOrderItems() as $orderItem) {
            $item = $ucm->findItem($orderItem->getItemId());

            if ($item) {
                $orderItem->setItem($item);
                $items[] = $orderItem;
            } else {
                ld('Товар '.$orderItem->getItemId().' из заказа '.$order->getId().'не доступен!'); // @todo обработку ошибок.
            }
        }

        /** @var UserModel $user */
        $user = $this->getUser();

        $order->setEmail($user->getEmailCanonical());

        $fio = $user->getFirstname().' '.$user->getLastname();
        if (empty(trim($fio))) {
            $order->setName($user->getUsername());
        } else {
            $order->setName($fio);
        }

        if (method_exists($user, 'getPhone')) {
            $order->setPhone($user->getPhone());
        }

        $form = $this->createForm(OrderConfirmFormType::class, $order);

        $form_data = $this->getFlashBag()->get('smart_shop_order_confirm_data');
        if (!empty($form_data)) {
            if ($form_data[0] instanceof \Knp\RadBundle\Flash\Message) { // @todo упростить
                $form_data[0] = $form_data[0]->getTemplate();
            }

            $form->submit(new Request($form_data[0]));
            $form->isValid();
        }

        return $this->render('ShopModule::order.html.twig', [
            'form'  => $form->createView(),
            'items' => $items,
            'order' => $order,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function myBasketAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $order = $em->getRepository('ShopModule:Order')->findOneBy(['status' => Order::STATUS_PENDING, 'user' => $this->getUser()]);

        // @todo вынести в сервис получение списка товаров в заказе.
        $items = [];
        if ($order) {
            $ucm = $this->get('unicat')->getConfigurationManager($this->get('settings')->get('shopmodule:catalog'));

            foreach ($order->getOrderItems() as $orderItem) {
                $item = $ucm->findItem($orderItem->getItemId());

                if ($item) {
                    $orderItem->setItem($item);
                    $items[] = $orderItem;
                } else {
                    ld('Товар '.$orderItem->getItemId().' из заказа '.$order->getId().'не доступен!'); // @todo обработку ошибок.
                }
            }
        }

        return $this->render('ShopModule::my_basket.html.twig', [
            'order'   => $order,
            'items'   => $items,
            'node_id' => $this->node->getId(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function basketWidgetAction()
    {
        if ($this->getUser() instanceof UserInterface) {
            /** @var \Doctrine\ORM\EntityManager $em */
            $em      = $this->get('doctrine.orm.entity_manager');

            $order = $em->getRepository('ShopModule:Order')->findOneBy([
                'status' => Order::STATUS_PENDING,
                'user'   => $this->getUser(),
            ]);
        } else {
            $order = null;
        }

        $qty = 0;

        if ($order) {
            foreach ($order->getOrderItems() as $orderItem) {
                $qty += $orderItem->getQuantity();
            }
        }

        return $this->render('ShopModule::basket_widget.html.twig', [
            'basket_url' => $this->get('cms.folder')->getUri($this->get('cms.node')->get($this->basket_node_id)),
            'order' => $order,
            'qty'   => $qty,
        ]);
    }

    /**
     * @param Request $request
     * @param int $item_id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showBuyButtonAction(Request $request, $item_id)
    {
        return $this->render('ShopModule::show_buy_button.html.twig', [
            'item_id'        => $item_id,
            'basket_node_id' => $this->basket_node_id,
            'basket_url'     => $this->get('cms.folder')->getUri($this->get('cms.node')->get($this->basket_node_id)),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function postAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        if ($request->request->has('smart_shop_order_confirm')) {
            $order = $em->getRepository('ShopModule:Order')->findOneBy(['status' => Order::STATUS_PENDING, 'user' => $this->getUser()]);

            $form = $this->createForm(OrderConfirmFormType::class, $order);
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var Order $order */
                $order = $form->getData();
                $order->setStatus(Order::STATUS_PROCESSING);

                $this->persist($order, true);
                $this->addFlash('success', 'Заказ оформлен.');
            } else {
                $this->addFlash('smart_shop_order_confirm_data', $request->request->all());
            }

            return $this->redirectToRoute('smart_module.shop.checkout');
        } elseif ($request->request->has('remove') and $request->request->has('order_item_id')) {
            return $this->removeItemToBasketAction($request);
        } elseif ($request->request->has('add')) {
            return $this->addItemToBasketAction($request);
        }

        $http_referer = $request->server->get('HTTP_REFERER');
        if (!empty($http_referer)) {
            return $this->redirect($http_referer);
        }

        return $this->redirect($request->getRequestUri());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeItemToBasketAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em      = $this->get('doctrine.orm.entity_manager');
        $session = $this->get('session')->getFlashBag();

        $orderItem = $em->find('ShopModule:OrderItem', $request->request->get('order_item_id'));

        if ($orderItem) {
            $order = $orderItem->getOrder();

            $this->remove($orderItem, true);

            $session->add('success', "Товар удалён из корзины.");

            // Пересчёт общей суммы заказа.
            if ($order and $order->getOrderItems()->count() == 0) {
                $order->setStatus(Order::STATUS_DELETED);
            } else {
                $amount = 0;
                foreach ($order->getOrderItems() as $orderItem) {
                    $amount += $orderItem->getAmount();
                }
                $order->setAmount($amount);
            }

            $this->persist($order, true);
        } else {
            $session->add('error', "Ошибка при удалении товара из корзины.");
        }


        $http_referer = $request->server->get('HTTP_REFERER');
        if (!empty($http_referer)) {
            return $this->redirect($http_referer);
        }

        return $this->redirect($request->getRequestUri());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addItemToBasketAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em      = $this->get('doctrine.orm.entity_manager');
        $session = $this->get('session')->getFlashBag();
        $ucm     = $this->get('unicat')->getConfigurationManager($this->get('settings')->get('shopmodule:catalog'));

        $order = null;

        $item_id = $request->request->get('item_id');
        $item    = $ucm->findItem($item_id);

        if (empty($item)) {
            $session->add('error', "Товар id: <b>{$item_id}</b> не доступен для заказа.");
        }

        $order = $em->getRepository('ShopModule:Order')->findOneBy([
            'status' => Order::STATUS_PENDING,
            'user'   => $this->getUser(),
        ]);

        if (empty($order)) {
            $order = new Order();
            $order
                ->setName($this->getUser()->getUsername())
                ->setUser($this->getUser())
                ->setUpdatedAt(new \DateTime())
            ;

            $this->persist($order, true);
        }

        $orderItem = $em->getRepository('ShopModule:OrderItem')->findOneBy(['order' => $order, 'item_id' => $item_id]);

        $price = $item->getAttr('price', 0);

        if ($orderItem) {
            $qty = $orderItem->getQuantity() + 1;
            $orderItem
                ->setQuantity($qty)
                ->setPrice($price)
                ->setAmount($price * $qty)
            ;
        } else {
            $orderItem = new OrderItem();
            $orderItem
                ->setItemId($item_id)
                ->setQuantity(1)
                ->setPrice($price)
                ->setAmount($price)
                ->setTitle($item->getAttr('title'))
                ->setUser($this->getUser())
            ;
        }

        $order->addOrderItem($orderItem);

        $this->persist($orderItem, true);

        $session->add('success', "Товар <b>{$item->getAttr('title')}</b> на сумму <b>{$item->getAttr('price')}</b> добавлен в корзину.");

        // Пересчёт общей суммы заказа.
        $amount = 0;

        foreach ($order->getOrderItems() as $orderItem) {
            $amount += $orderItem->getAmount();
        }
        $order->setAmount($amount);
        $this->persist($order, true);


        $http_referer = $request->server->get('HTTP_REFERER');
        if (!empty($http_referer)) {
            return $this->redirect($http_referer);
        }

        return $this->redirect($request->getRequestUri());
    }
}
