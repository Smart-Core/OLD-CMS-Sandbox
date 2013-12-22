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

        $this->View->news = $em->getRepository('NewsModule:News')->findAll();

        $response = new Response($this->View);
        // @todo EIP.
        return $response;
    }
}
