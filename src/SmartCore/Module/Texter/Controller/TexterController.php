<?php

namespace SmartCore\Module\Texter\Controller;

use Symfony\Component\HttpFoundation\Response;

class TexterController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $item = $this->get('smart_module_texter')->get($this->text_item_id, $this->node->getId());

        $this->node->addFrontControl('edit', [
            'title'   => 'Редактировать',
            'descr'   => 'Текстовый блок',
            'uri'     => $this->generateUrl('cms_admin_node', ['id' => $this->node->getId()]),
            'default' => true,
        ]);

        if ($item) {
            return new Response($item->getText());
        }

        return new Response('Texter not found. Node: ' . $this->node->getId() . '<br />', 404);
    }
}
