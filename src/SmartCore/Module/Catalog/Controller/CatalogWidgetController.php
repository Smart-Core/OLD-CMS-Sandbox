<?php

namespace SmartCore\Module\Catalog\Controller;

use SmartCore\Bundle\CMSBundle\Module\CacheTrait;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CatalogWidgetController extends Controller
{
    use CacheTrait;
    use NodeTrait;

    /**
     * @var int
     */
    protected $repository_id;

    /**
     * @param int|null $depth
     * @return Response
     */
    public function categoryTreeAction($depth = null, $css_class = null, $template = 'knp_menu.html.twig')
    {
        $urm = $this->get('unicat')->getRepositoryManager($this->repository_id);

        // @todo cache
        $categoryTree = $this->renderView('UnicatBundle::category_tree.html.twig', [
            'categoryClass' => $urm->getCategoryClass(),
            'css_class'     => $css_class,
            'depth'         => $depth,
            'routeName'     => 'smart_module.catalog.category',
            'template'      => $template,
        ]);

        return new Response($categoryTree);
    }
}
