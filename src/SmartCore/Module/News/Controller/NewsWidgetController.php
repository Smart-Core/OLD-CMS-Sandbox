<?php

namespace SmartCore\Module\News\Controller;

use SmartCore\Bundle\CMSBundle\Module\CacheTrait;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NewsWidgetController extends Controller
{
    use CacheTrait;
    use NodeTrait;

    /**
     * Последние новости.
     *
     * @param  int $count
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lastAction($count = 5)
    {
        $cacheKey = md5('smart_module.news.widget_last' . $count);

        if (false === $news = $this->getCacheService()->get($cacheKey)) {
            $repoNews = $this->getDoctrine()->getRepository('NewsModule:News');

            $news = $this->renderView('NewsModule:Widget:last.html.twig', [
                'news' => $repoNews->findBy([], ['created' => 'DESC'], $count)
            ]);

            $this->getCacheService()->set($cacheKey, $news, ['smart_module.news', 'node_'.$this->node->getId(), 'node']);
        }

        return new Response($news);
    }
}
