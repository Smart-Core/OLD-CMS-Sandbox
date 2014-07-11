<?php

namespace SmartCore\Bundle\CMSBundle\Twig;

use SmartCore\Bundle\CMSBundle\Entity\Block;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CmsExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            'cms_nodes_count_in_block' => new \Twig_Function_Method($this, 'nodesCountInBlock'),
        ];
    }

    /**
     * @param  Block|int $block
     * @return int
     */
    public function nodesCountInBlock($block)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        return $em->getRepository('CMSBundle:Node')->countInBlock($block);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smart_core_cms_twig_extension';
    }
}
