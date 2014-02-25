<?php

namespace SmartCore\Module\Catalog\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $urm = $this->get('unicat')->getRepositoryManager($this->repository_id);

        return $this->render('CatalogModule::items.html.twig', [
            'items' => $urm ? $urm->findAllItems(['id' => 'DESC']) : [],
        ]);
    }

    /**
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($slug)
    {
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

        $this->get('html')->setMetas($lastCategory->getMeta());

        return $this->render('CatalogModule::items.html.twig', [
            'items' => false, // $urm ? $urm->findAllItems(['id' => 'DESC']) : [],
            'category' => $lastCategory,
        ]);
    }
}
