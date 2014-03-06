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
     * @param int $depth
     * @param string $css_class
     * @param string $template
     * @param bool $selected_inheritance
     * @param int $structure
     * @return Response
     */
    public function categoryTreeAction(
        $css_class = null,
        $depth = null,
        $template = 'knp_menu.html.twig',
        $selected_inheritance = false,
        $structure = null
    ) {
        if (null === $this->repository_id) {
            return new Response('Module Catalog not yet configured. Node: ' . $this->node->getId() . '<br />');
        }

        $urm = $this->get('unicat')->getRepositoryManager($this->repository_id);

        // Хак для Menu\RequestVoter
        $this->get('request')->attributes->set('__selected_inheritance', $selected_inheritance);

        // @todo cache
        $categoryTree = $this->renderView('UnicatBundle::category_tree.html.twig', [
            'categoryClass' => $urm->getCategoryClass(),
            'css_class'     => $css_class,
            'depth'         => $depth,
            'routeName'     => 'smart_module.catalog.category',
            'structure'     => empty($structure) ? $urm->getDefaultStructure() : $urm->getStructure($structure),
            'template'      => $template,
        ]);

        $this->get('request')->attributes->remove('__selected_inheritance');

        return new Response($categoryTree);
    }
}
