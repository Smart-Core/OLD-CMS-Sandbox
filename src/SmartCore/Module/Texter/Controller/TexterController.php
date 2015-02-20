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
        $nodeId = $this->node->getId();

        if ($item = $this->get('smart_module.texter')->get($this->text_item_id, $nodeId)) {
            $this->node->addFrontControl('edit')
                ->setTitle('Редактировать текст')
                ->setUri($this->generateUrl('cms_admin_node', ['id' => $nodeId]));

            return new Response($item->getText());
        }

        return new Response("Texter not found. Node: $nodeId<br />\n");
    }
}
