<?php

namespace SmartCore\Module\Blog\Controller;

use Pagerfanta\Pagerfanta;
use Smart\CoreBundle\Pagerfanta\SimpleDoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;

class BlogWidgetController extends Controller
{
    use NodeTrait;

    /**
     * @param Request $requst
     * @param integer $page
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function mainAction()
    {
        $articleService = $this->get('smart_blog.article');

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($articleService->getFindByCategoryQuery()));
        $pagerfanta->setMaxPerPage($articleService->getItemsCountPerPage());

        return $this->render('BlogModule:Widget:main.html.twig', [
            'pagerfanta' => $pagerfanta,
        ]);
    }

    /**
     * @param int $limit
     *
     * @return Response
     */
    public function archiveMonthlyAction($limit = 24)
    {
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get('smart_blog.article');

        if (false === $archive = $articleService->getCache()->fetch('archive_monthly')) {
            $archive = $this->get('twig')->render('BlogModule:Widget:archive_articles.html.twig', [
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
        /** @var \SmartCore\Module\Blog\Service\CategoryService $categoryService */
        $categoryService = $this->get('smart_blog.category');

        if (false === $categoryTree = $categoryService->getCache()->fetch('knp_menu_category_tree')) {
            $categoryTree = $this->get('twig')->render('BlogModule:Widget:category_tree.html.twig', [
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
        /** @var \SmartCore\Module\Blog\Service\TagService $tagService */
        $tagService = $this->get('smart_blog.tag');

        if (false === $cloud = $tagService->getCache()->fetch('tag_cloud_zend')) {
            $cloud = $tagService->getCloudZend('smart_blog_tag')->render();
            $tagService->getCache()->save('tag_cloud_zend', $cloud);
        }

        return new Response($cloud);
    }
}
