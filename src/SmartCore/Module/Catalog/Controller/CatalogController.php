<?php

namespace SmartCore\Module\Catalog\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CatalogController extends Controller
{
    use NodeTrait;

    /**
     * @var int
     */
    protected $repository_id;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->categoryAction();
    }

    /**
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($slug = null)
    {
        if (null === $this->repository_id) {
            return new Response('Module Catalog not yet configured. Node: ' . $this->node->getId() . '<br />');
        }

        $urm = $this->get('unicat')->getRepositoryManager($this->repository_id);

        // @todo убрать в URM

        $requestedCategories = [];
        $parent = null;
        foreach (explode('/', $slug) as $categoryName) {
            if (strlen($categoryName) == 0) {
                break;
            }

            /** @var CategoryModel $category */
            $category = $urm->getCategoryRepository()->findOneBy([
                'is_enabled' => true,
                'parent' => $parent,
                'slug'   => $categoryName,
            ]);

            if ($category) {
                $requestedCategories[] = $category;
                $parent = $category;

                $this->get('cms.breadcrumbs')->add($this->generateUrl('smart_module.catalog.category', ['slug' => $category->getSlugFull()]) . '/', $category->getTitle());
            } else {
                throw $this->createNotFoundException();
            }
        }

        /** @var CategoryModel $lastCategory */
        $lastCategory = end($requestedCategories);

        if ($lastCategory instanceof CategoryModel) {
            $this->get('html')->setMetas($lastCategory->getMeta());
            $childenCategories = $lastCategory->getChildren();
        } else {
            $childenCategories = $urm->getCategoryRepository()->findBy([
                'parent' => null,
            ]);
        }

        return $this->render('CatalogModule::items.html.twig', [
            'childenCategories' => $childenCategories,
            'items'             => $lastCategory ? $urm->findItemsInCategory($lastCategory) : null,
        ]);
    }
}
