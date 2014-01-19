<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\CMSBundle\Response;
use Symfony\Component\HttpFoundation\Request;

class TexterController extends Controller
{
    /**
     * Экшен по умолчанию.
     *
     * @param integer $item_id
     */
    public function indexAction($item_id = null)
    {
        $cache_key = md5('smart_module_texter' . $this->text_item_id);

        /** @var \RickySu\Tagcache\Adapter\TagcacheAdapter $tagcache */
        $tagcache = $this->get('tagcache');

        if (false == $item = $tagcache->get($cache_key)) {
            $item = $this->getDoctrine()->getManager()->find('TexterModule:Item', $item_id ? $item_id : $this->text_item_id);

            $tagcache->set($cache_key, $item, ['smart_module_texter', 'node_'.$this->node->getId()]);
        }

        $this->View
            ->setEngine('echo')
            ->set('text', $item->getText());

        foreach ($item->getMeta() as $key => $value) {
            $this->get('html')->meta($key, $value);
        }

        $response = new Response($this->View);

        if ($this->isEip()) {
            $response->setFrontControls([
                'edit' => [
                    'title'   => 'Редактировать',
                    'descr'   => 'Текстовый блок',
                    'uri'     => $this->generateUrl('cms_admin_node', ['id' => $this->node->getId()]),
                    'default' => true,
                ],
            ]);
        }

        return $response;
    }
}
