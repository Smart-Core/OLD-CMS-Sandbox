<?php

namespace SmartCore\Module\News\Controller;

use SmartCore\Bundle\CMSBundle\Response;
use SmartCore\Bundle\CMSBundle\Module\Controller;

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

    /**
     * @param string $slug
     * @return Response
     */
    public function itemAction($slug)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        // @todo breadcrumps
        $this->View->setTemplateName('item');
        $this->View->item = $em->getRepository('NewsModule:News')->findOneBy(['slug' => $slug]);

        if (empty($this->View->item)) {
            throw $this->createNotFoundException();
        }

        $response = new Response($this->View);
        // @todo EIP.
        return $response;
    }
}
