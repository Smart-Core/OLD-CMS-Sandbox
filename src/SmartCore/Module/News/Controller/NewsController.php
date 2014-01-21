<?php

namespace SmartCore\Module\News\Controller;

use SmartCore\Bundle\CMSBundle\Response;
use SmartCore\Bundle\CMSBundle\Module\Controller;

class NewsController extends Controller
{
    protected function init()
    {
        $this->view->setEngine('twig');
    }

    public function indexAction($page_num = 1)
    {
        $this->view->news = $this->getDoctrine()->getRepository('NewsModule:News')->findBy([], ['id' => 'DESC']);

        $response = new Response($this->view);

        if ($this->isEip()) {
            $response->setFrontControls([
                'create' => [
                    'title'   => 'Добавить',
                    'descr'   => 'Добавить новость',
                    'uri'     => $this->generateUrl('smart_news_admin_create'),
                    'default' => true,
                ],
            ]);
        }

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

        $this->view->setTemplateName('item');
        $this->view->set('item', $item);

        $response = new Response($this->view);

        if ($this->isEip()) {
            $response->setFrontControls([
                'edit' => [
                    'title'   => 'Редактировать',
                    'descr'   => 'Редактировать новость',
                    'uri'     => $this->generateUrl('smart_news_admin_edit', ['id' => $item->getId()]),
                    'default' => true,
                ],
                'create' => [
                    'title'   => 'Добавить',
                    'descr'   => 'Добавить новость',
                    'uri'     => $this->generateUrl('smart_news_admin_create'),
                ],
            ]);
        }

        return $response;
    }
}
