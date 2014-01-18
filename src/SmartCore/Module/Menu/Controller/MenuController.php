<?php

namespace SmartCore\Module\Menu\Controller;

use SmartCore\Bundle\CMSBundle\Response;

class MenuController extends Controller
{
    public function indexAction()
    {
        $cache_key = md5($this->get('request')->getRequestUri() . md5(serialize($this->node)));

        //if (null == $this->View->menu = $this->getCache($cache_key)) { // @todo инвалидацию кеша.
        if (true) {
            $em = $this->get('doctrine.orm.default_entity_manager');

            // Хак для Menu\RequestVoter
            $this->get('request')->attributes->set('__selected_inheritance', $this->selected_inheritance);

            $this->View->menu = $this->renderView('MenuModule::menu.html.twig', [
                'group' => $em->find('MenuModule:Group', $this->group_id),
                'css_class' => $this->css_class,
                'current_class' => '',
                'depth' => $this->depth,
            ]);

            $this->setCache($cache_key, $this->View->menu);

            $this->get('request')->attributes->set('__selected_inheritance', false);
        }

        $response = new Response($this->View);

        if ($this->isEip()) {
            $this->setFrontControls($response);
        }

        return $response;
    }

    protected function setFrontControls(Response $response)
    {
        $response->setFrontControls([
            'edit' => [
                'title' => 'Редактировать',
                'descr' => 'Пункты меню',
                'uri'   => $this->generateUrl('cms_admin_node_w_slug', [
                        'id'   => $this->node->getId(),
                        'slug' => $this->group_id,
                    ]),
                'overlay' => false,
                'default' => false,
            ],
        ]);
    }
}
