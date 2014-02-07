<?php

namespace SmartCore\Module\Blog\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WidgetController extends Controller
{
    /**
     * Имя бандла. Для перегрузки шаблонов.
     *
     * @var string
     */
    protected $bundleName;

    /**
     * Имя сервиса по работе с категориями.
     *
     * @var string
     */
    protected $categoryServiceName;

    /**
     * Имя сервиса по работе со статьями.
     *
     * @var string
     */
    protected $articleServiceName;

    /**
     * Маршрут просмотра списка статей по тэгу.
     *
     * @var string
     */
    protected $routeTag;

    /**
     * Имя сервиса по работе с тэгами.
     *
     * @var string
     */
    protected $tagServiceName;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bundleName           = 'SmartBlogBundle';

        $this->articleServiceName   = 'smart_blog.article';
        $this->categoryServiceName  = 'smart_blog.category';
        $this->tagServiceName       = 'smart_blog.tag';
        $this->routeTag             = 'smart_blog_tag';
    }

    /**
     * @param integer $limit
     * @return Response
     */
    public function archiveMonthlyAction($limit = 24)
    {
        /** @var \SmartCore\Bundle\BlogBundle\Service\ArticleService $articleService */
        $articleService = $this->get($this->articleServiceName);
        $archive        = $articleService->getCache()->fetch('archive_monthly');

        if (false === $archive) {
            $archive = $this->renderView($this->bundleName . ':Widget:archive_articles.html.twig', [
                'archiveMonthly' => $articleService->getArchiveMonthly($limit),
            ]);

            $articleService->getCache()->save('archive_monthly', $archive);
        }

        return new Response($archive);
    }

    /**
     * @return Response
     */
    public function categoryTreeAction()
    {
        /** @var \SmartCore\Bundle\BlogBundle\Service\CategoryService $categoryService */
        $categoryService = $this->get($this->categoryServiceName);
        $categoryTree    = $categoryService->getCache()->fetch('knp_menu_category_tree');

        if (false === $categoryTree) {
            $categoryTree = $this->renderView($this->bundleName . ':Widget:category_tree.html.twig', [
                'categoryClass' => $categoryService->getCategoryClass(),
            ]);
            $categoryService->getCache()->save('knp_menu_category_tree', $categoryTree);
        }

        return new Response($categoryTree);
    }

    /**
     * @return Response
     */
    public function tagCloudAction()
    {
        /** @var \SmartCore\Bundle\BlogBundle\Service\TagService $tagService */
        $tagService = $this->get($this->tagServiceName);
        $cloud      = $tagService->getCache()->fetch('tag_cloud_zend');

        if (false === $cloud) {
            $cloud = $tagService->getCloudZend($this->routeTag)->render();
            $tagService->getCache()->save('tag_cloud_zend', $cloud);
        }

        return new Response($cloud);
    }
}
