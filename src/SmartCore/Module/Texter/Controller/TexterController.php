<?php

namespace SmartCore\Module\Texter\Controller;

use Symfony\Component\HttpFoundation\Response;

class TexterController extends Controller
{
    /**
     * @param integer $item_id
     * @return Response
     */
    public function indexAction($item_id = null)
    {
        $cache_key = md5('smart_module_texter' . $this->text_item_id);

        $cache = $this->getCacheService();

        if (false == $item = $cache->get($cache_key)) {
            $item = $this->getDoctrine()->getManager()->find('TexterModule:Item', $item_id ? $item_id : $this->text_item_id);

            $cache->set($cache_key, $item, ['smart_module_texter', 'node_'.$this->node->getId()]);
        }

        foreach ($item->getMeta() as $key => $value) {
            $this->get('html')->meta($key, $value);
        }

        $this->node->addFrontControl('edit', [
            'title'   => 'Редактировать',
            'descr'   => 'Текстовый блок',
            'uri'     => $this->generateUrl('cms_admin_node', ['id' => $this->node->getId()]),
            'default' => true,
        ]);

        return new Response($item->getText());
    }
}
