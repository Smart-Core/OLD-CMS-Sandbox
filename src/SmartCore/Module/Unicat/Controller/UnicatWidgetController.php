<?php

namespace SmartCore\Module\Unicat\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Smart\CoreBundle\Pagerfanta\SimpleDoctrineORMAdapter;
use SmartCore\Bundle\CMSBundle\Module\CacheTrait;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UnicatWidgetController extends Controller
{
    use CacheTrait;
    use NodeTrait;

    /**
     * @var int
     */
    protected $configuration_id;

    /**
     * @param int $depth
     * @param string $css_class
     * @param string $template
     * @param bool $selected_inheritance
     * @param int $structure
     *
     * @return Response
     */
    public function categoryTreeAction(
        $css_class = null,
        $depth = null,
        $template = 'knp_menu.html.twig',
        $selected_inheritance = false,
        $structure = null
    ) {
        $ucm = $this->get('unicat')->getConfigurationManager($this->configuration_id);

        // Хак для Menu\RequestVoter
        $this->get('request')->attributes->set('__selected_inheritance', $selected_inheritance);

        // @todo cache
        $categoryTree = $this->renderView('UnicatModule::category_tree.html.twig', [
            'categoryClass' => $ucm->getCategoryClass(),
            'css_class'     => $css_class,
            'depth'         => $depth,
            'routeName'     => 'unicat.category',
            'structure'     => empty($structure) ? $ucm->getDefaultStructure() : $ucm->getStructure($structure),
            'template'      => $template,
        ]);

        $this->get('request')->attributes->remove('__selected_inheritance');

        return new Response($categoryTree);
    }

    public function getItemsAction(array $criteria, array $orderBy = null, $limit = 10, $offset = null)
    {
        $ucm = $this->get('unicat')->getConfigurationManager($this->configuration_id);

        //$pagerfanta = new Pagerfanta(new DoctrineORMAdapter($ucm->getFindItemsQuery($criteria, $orderBy, $limit, $offset)));
        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($ucm->getFindItemsQuery($criteria, $orderBy, $limit, $offset)));
        $pagerfanta->setMaxPerPage($limit);

        try {
            $pagerfanta->setCurrentPage(1);
        } catch (NotValidCurrentPageException $e) {
            return $this->createNotFoundException('Такой страницы не найдено');
        }

        return $this->render('UnicatModule::items.html.twig', [
            'mode'              => 'list',
            'attributes'        => $ucm->getAttributes(),
            'configuration'     => $ucm->getConfiguration(),
            'lastCategory'      => null,
            'childenCategories' => null,
            'pagerfanta'        => $pagerfanta,
            'slug'              => null,
        ]);
    }
}
