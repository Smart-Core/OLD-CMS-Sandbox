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
            $this->node->addFrontControl('edit', [
                'title'   => 'Редактировать текст',
                'descr'   => $this->node->getDescr(),
                'uri'     => $this->generateUrl('cms_admin_node', ['id' => $nodeId]),
                'default' => true,
            ]);

            return new Response($item->getText());
        }

        return new Response("Texter not found. Node: $nodeId<br />\n");
    }
}
