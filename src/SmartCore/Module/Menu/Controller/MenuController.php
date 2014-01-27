<?php

namespace SmartCore\Module\Menu\Controller;

use Symfony\Component\HttpFoundation\Response;

class MenuController extends Controller
{
    public function indexAction()
    {
        if (null === $this->group_id) {
            return new Response('Module Menu not yet configured. Node: ' . $this->node->getId() . '<br />');
        }

        $current_folder_path = $this->get('cms.context')->getCurrentFolderPath();

        $cache_key = md5('smart_module_menu' . $current_folder_path . $this->node->getId());

        if (false == $menu = $this->getCacheService()->get($cache_key)) {
            // Хак для Menu\RequestVoter
            $this->get('request')->attributes->set('__selected_inheritance', $this->selected_inheritance);
            $this->get('request')->attributes->set('__current_folder_path', $current_folder_path);

            $menu = $this->renderView('MenuModule::' . $this->node->getTemplate('menu') . '.html.twig', [
                'css_class'     => $this->css_class,
                'current_class' => '',
                'depth'         => $this->depth,
                'group'         => $this->getDoctrine()->getManager()->find('MenuModule:Group', $this->group_id),
            ]);

            //$menu = $this->get('html.tidy')->prettifyFragment($menu);

            $this->getCacheService()->set($cache_key, $menu, ['smart_module_menu', 'folder', 'node_'.$this->node->getId()]);

            $this->get('request')->attributes->remove('__selected_inheritance');
            $this->get('request')->attributes->remove('__current_folder_path');
        }

        $this->setFrontControls();

        return new Response($menu);
    }

    protected function setFrontControls()
    {
        $this->node->addFrontControl('edit', [
            'title'    => 'Редактировать',
            'default'  => true,
            'descr'    => 'Пункты меню',
            'uri'      => $this->generateUrl('cms_admin_node_w_slug', [
                'id'   => $this->node->getId(),
                'slug' => $this->group_id,
            ]),
        ]);
    }
}
