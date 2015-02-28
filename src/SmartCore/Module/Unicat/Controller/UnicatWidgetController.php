<?php

namespace SmartCore\Module\Unicat\Controller;

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
     * @return Response
     */
    public function categoryTreeAction(
        $css_class = null,
        $depth = null,
        $template = 'knp_menu.html.twig',
        $selected_inheritance = false,
        $structure = null
    ) {
        if (null === $this->configuration_id) {
            return new Response('Module Unicat not yet configured. Node: '.$this->node->getId().'<br />');
        }

        $ucm = $this->get('unicat')->getConfigurationManager($this->configuration_id);

        // Хак для Menu\RequestVoter
        $this->get('request')->attributes->set('__selected_inheritance', $selected_inheritance);

        // @todo cache
        $categoryTree = $this->renderView('UnicatModule::category_tree.html.twig', [
            'categoryClass' => $ucm->getCategoryClass(),
            'css_class'     => $css_class,
            'depth'         => $depth,
            'routeName'     => 'smart_module.unicat.category',
            'structure'     => empty($structure) ? $ucm->getDefaultStructure() : $ucm->getStructure($structure),
            'template'      => $template,
        ]);

        $this->get('request')->attributes->remove('__selected_inheritance');

        return new Response($categoryTree);
    }
}
