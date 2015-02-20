<?php

namespace SmartCore\Module\Blog\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Smart\CoreBundle\Pagerfanta\SimpleDoctrineORMAdapter;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Blog\Model\CategoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    use NodeTrait;

    /**
     * @param Request $request
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlesAction(Request $requst, $slug = null)
    {
        if (empty($slug)) {
            return $this->redirect($this->generateUrl('smart_blog.article.index'));
        }

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

                $this->get('cms.breadcrumbs')->add($this->generateUrl('smart_blog.category.articles', ['slug' => $category->getSlugFull()]).'/', $category->getTitle());
            } else {
                throw $this->createNotFoundException();
            }
        }

        /** @var CategoryInterface $lastCategory */
        $lastCategory = end($requestedCategories);

        $categories = new ArrayCollection();
        $categories->add($lastCategory);

        $this->addChild($categories, $lastCategory);

        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get('smart_blog.article');

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($articleService->getFindByCategoriesQuery($categories->getValues())));
        $pagerfanta->setMaxPerPage($articleService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($requst->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_blog.article.index'));
        }

        $this->node->addFrontControl('edit')
            ->setTitle('Редактировать категории')
            ->setUri($this->generateUrl('smart_blog_admin_category'));

        return $this->render('BlogModule:Category:articles.html.twig', [
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
     * @return \SmartCore\Module\Blog\Service\CategoryService
     */
    protected function getCategoryService()
    {
        return $this->get('smart_blog.category');
    }
}
