<?php

namespace SmartCore\Module\News\Controller;

use SmartCore\Bundle\CMSBundle\Module\Controller;

class NewsController extends Controller
{
    public function indexAction($page_num = 1)
    {
        $this->node->addFrontControl('create', [
            'title'   => 'Добавить',
            'descr'   => 'Добавить новость',
            'uri'     => $this->generateUrl('smart_news_admin_create'),
            'default' => true,
        ]);

        return $this->render('NewsModule::news.html.twig', [
            'node' => $this->node, // @todo подумать как шаблону передавать контекст ноды.
            'news' => $this->getDoctrine()->getRepository('NewsModule:News')->findBy([], ['id' => 'DESC'])
        ]);
    }

    /**
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemAction($slug)
    {
        $item = $this->getDoctrine()->getRepository('NewsModule:News')->findOneBy(['slug' => $slug]);

        if (empty($item)) {
            throw $this->createNotFoundException();
        }

        $this->get('cms.breadcrumbs')->add($item->getSlug(), $item->getTitle());

        $this->node->setFrontControls([
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

        return $this->render('NewsModule::item.html.twig', ['item' => $item ]);
    }
}
