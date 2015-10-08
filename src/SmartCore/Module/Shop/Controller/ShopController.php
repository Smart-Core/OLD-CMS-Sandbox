<?php

namespace SmartCore\Module\Shop\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
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

    public function myBasketAction()
    {
        return $this->render('ShopModule::my_basket.html.twig', []);
    }

    public function basketWidgetAction()
    {
        return $this->render('ShopModule::basket_widget.html.twig', []);
    }

    public function showBuyButtonAction(Request $request, $item_id)
    {
        return $this->render('ShopModule::show_buy_button.html.twig', [
            'item_id' => $item_id,
            'basket_node_id' => $this->basket_node_id,
            'basket_url' => $this->get('cms.folder')->getUri($this->get('cms.node')->get($this->basket_node_id)),
        ]);
    }

    public function postAction(Request $request)
    {
        $ucm = $this->get('unicat')->getConfigurationManager($this->get('settings')->get('shopmodule', 'catalog'));

        $item_id = $request->request->get('item_id');
        $item = $ucm->findItem($item_id);

//        dump($item->getAttr('price'));
//        dump($request);
//        die;

        $session = $this->get('session')->getFlashBag();
        $session->add('success', "Товар <b>{$item->getAttr('title')}</b> на сумму <b>{$item->getAttr('price')}</b> добавлен в корзину.");

        $http_referer = $request->server->get('HTTP_REFERER');
        if (!empty($http_referer)) {
            return $this->redirect($http_referer);
        }

        return $this->redirect($request->getRequestUri());
    }
}
