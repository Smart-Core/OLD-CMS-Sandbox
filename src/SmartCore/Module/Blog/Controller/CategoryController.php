<?php

namespace SmartCore\Module\Blog\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Module\Blog\Model\CategoryInterface;
use SmartCore\Module\Blog\Pagerfanta\SimpleDoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * Имя бандла. Для перегрузки шаблонов.
     *
     * @var string
     */
    protected $bundleName;

    /**
     * Имя сервиса по работе со статьями.
     *
     * @var string
     */
    protected $articleServiceName;

    /**
     * Имя сервиса по работе с категориями.
     *
     * @var string
     */
    protected $categoryServiceName;

    /**
     * Маршрут на список категорий.
     *
     * @var string
     */
    protected $routeIndex;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bundleName           = 'SmartBlogBundle';

        $this->articleServiceName   = 'smart_blog.article';
        $this->categoryServiceName  = 'smart_blog.category';
        $this->routeIndex           = 'smart_blog.category.articles';
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlesAction(Request $requst, $slug = null)
    {
        $requestedCategories = [];
        $parent = null;
        foreach (explode('/', $slug) as $categoryName) {
            if (strlen($categoryName) == 0) {
                break;
            }

            $category = $this->getCategoryService()->findOneBy([
                'parent' => $parent,
                'slug'   => $categoryName,
            ]);

            if ($category) {
                $requestedCategories[] = $category;
                $parent = $category;
            } else {
                throw $this->createNotFoundException();
            }
        }

        /** @var CategoryInterface $lastCategory */
        $lastCategory = end($requestedCategories);

        $categories = new ArrayCollection();
        $categories->add($lastCategory);

        $this->addChild($categories, $lastCategory);

        /** @var \SmartCore\Bundle\BlogBundle\Service\ArticleService $articleService */
        $articleService = $this->get($this->articleServiceName);

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($articleService->getFindByCategoriesQuery($categories->getValues())));
        $pagerfanta->setMaxPerPage($articleService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($requst->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl($this->routeIndex));
        }

        return $this->render($this->bundleName . ':Category:articles.html.twig', [
            'categories'    => $requestedCategories,
            'lastCategory'  => $lastCategory,
            'pagerfanta'    => $pagerfanta,
        ]);
    }

    /**
     * Получение всех вложенных категорий.
     *
     * @param ArrayCollection $categories
     * @param CategoryInterface|null $parent
     */
    protected function addChild(ArrayCollection $categories, CategoryInterface $parent = null)
    {
        if (null === $parent) {
            return;
        } else {
            $children = $parent->getChildren();
        }

        /** @var CategoryInterface $category */
        foreach ($children as $category) {
            $categories->add($category);
            $this->addChild($categories, $category);
        }
    }

    /**
     * @return \SmartCore\Bundle\BlogBundle\Service\CategoryService
     */
    protected function getCategoryService()
    {
        return $this->get($this->categoryServiceName);
    }
}
