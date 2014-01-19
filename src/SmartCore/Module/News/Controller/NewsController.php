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
        $this->View->news = $this->getDoctrine()->getRepository('NewsModule:News')->findBy([], ['id' => 'DESC']);

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
        $item = $this->getDoctrine()->getRepository('NewsModule:News')->findOneBy(['slug' => $slug]);

        if (empty($item)) {
            throw $this->createNotFoundException();
        }

        $this->get('cms.breadcrumbs')->add($item->getSlug(), $item->getTitle());

        $this->View->setTemplateName('item');
        $this->View->set('item', $item);

        $response = new Response($this->View);
        // @todo EIP.
        return $response;
    }
}
