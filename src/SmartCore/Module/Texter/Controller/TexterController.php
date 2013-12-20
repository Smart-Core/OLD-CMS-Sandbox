<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\EngineBundle\Response;
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
        $em = $this->get('doctrine.orm.default_entity_manager');

        $item = $em->find('TexterModule:Item', $item_id ? $item_id : $this->text_item_id);

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
                    'uri'     => $this->generateUrl('cmf_admin_node', ['id' => $this->node->getId()]),
                    'default' => true,
                ],
            ]);
        }

        return $response;
    }
}
