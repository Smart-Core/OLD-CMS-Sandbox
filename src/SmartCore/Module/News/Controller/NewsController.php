<?php

namespace SmartCore\Module\News\Controller;

use SmartCore\Bundle\EngineBundle\Response;
use SmartCore\Bundle\EngineBundle\Module\Controller;

class NewsController extends Controller
{
    protected function init()
    {
        $this->View->setEngine('twig');
    }

    public function indexAction($page_num = 1)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        // @todo продумать как проще пообщаться модулю его путь к папке.
        $this->View->node = $this->node;
        $this->View->node_uri = $this->get('engine.folder')->getUri($this->node->getFolderId());
        $this->View->news = $em->getRepository('NewsModule:News')->findAll();

        $response = new Response($this->View);
        // @todo EIP.
        return $response;
    }
}
