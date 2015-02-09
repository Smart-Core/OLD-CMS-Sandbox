<?php

namespace SmartCore\Module\SimpleNews\Controller;

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
        $cacheKey = md5('smart_module.news.widget_last'.$count);

        if (false === $news = $this->getCacheService()->get($cacheKey)) {
            $news = $this->renderView('SimpleNewsModule:Widget:last.html.twig', [
                'news' => $this->getDoctrine()->getRepository('SimpleNewsModule:News')->findLastEnabled($count),
            ]);

            $this->getCacheService()->set($cacheKey, $news, ['smart_module.news', 'node_'.$this->node->getId(), 'node']);
        }

        return new Response($news);
    }
}
