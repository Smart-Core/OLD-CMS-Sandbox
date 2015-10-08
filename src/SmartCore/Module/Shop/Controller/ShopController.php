<?php

namespace SmartCore\Module\Shop\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Shop\Entity\Order;
use SmartCore\Module\Shop\Entity\OrderItem;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends Controller
{
    use NodeTrait;

    protected $mode;
    protected $basket_node_id = 36; // @todo глобальную настройку ноды корзинки

    public function indexAction()
    {
        switch ($this->mode) {
            case 'basket_widget':
                return $this->basketWidgetAction();
            case 'basket':
                return $this->myBasketAction();
        }

        return $this->render('ShopModule::index.html.twig', []);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function myBasketAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $order = $em->getRepository('ShopModule:Order')->findOneBy(['status' => Order::STATUS_PENDING]);

        $items = [];

        if ($order) {
            $ucm = $this->get('unicat')->getConfigurationManager($this->get('settings')->get('shopmodule', 'catalog'));

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
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function basketWidgetAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em      = $this->get('doctrine.orm.entity_manager');

        $order = $em->getRepository('ShopModule:Order')->findOneBy(['status' => Order::STATUS_PENDING]);

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

    public function showBuyButtonAction(Request $request, $item_id)
    {
        return $this->render('ShopModule::show_buy_button.html.twig', [
            'item_id' => $item_id,
            'basket_node_id' => $this->basket_node_id,
            'basket_url' => $this->get('cms.folder')->getUri($this->get('cms.node')->get($this->basket_node_id)),
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
        $em      = $this->get('doctrine.orm.entity_manager');
        $session = $this->get('session')->getFlashBag();
        $ucm     = $this->get('unicat')->getConfigurationManager($this->get('settings')->get('shopmodule', 'catalog'));

        $item_id = $request->request->get('item_id');
        $item    = $ucm->findItem($item_id);

        if (empty($item)) {
            $session->add('error', "Товар id: <b>{$item_id}</b> не доступен для заказа.");
        }

        $order = $em->getRepository('ShopModule:Order')->findOneBy(['status' => Order::STATUS_PENDING]);

        if (empty($order)) {
            $order = new Order();
            $order
                ->setUserId($this->getUser()->getId())
                ->setUpdatedAt(new \DateTime())
            ;

            $this->persist($order, true);
        }

        $orderItem = $em->getRepository('ShopModule:OrderItem')->findOneBy(['order' => $order, 'item_id' => $item_id]);

        if ($orderItem) {
            $qty = $orderItem->getQuantity() + 1;
            $orderItem
                ->setQuantity($qty)
                ->setPrice($item->getAttr('price'))
                ->setAmount($item->getAttr('price') * $qty)
            ;
        } else {
            $orderItem = new OrderItem();
            $orderItem
                ->setItemId($item_id)
                ->setQuantity(1)
                ->setPrice($item->getAttr('price'))
                ->setAmount($item->getAttr('price'))
                ->setOrder($order)
            ;
        }

        $this->persist($orderItem, true);

        // Пересчёт общей суммы заказа.
        $amount = 0;
        foreach ($order->getOrderItems() as $orderItem) {
            $amount += $orderItem->getAmount();
        }
        $order->setAmount($amount);
        $this->persist($order, true);

        $session->add('success', "Товар <b>{$item->getAttr('title')}</b> на сумму <b>{$item->getAttr('price')}</b> добавлен в корзину.");

        $http_referer = $request->server->get('HTTP_REFERER');
        if (!empty($http_referer)) {
            return $this->redirect($http_referer);
        }

        return $this->redirect($request->getRequestUri());
    }
}
