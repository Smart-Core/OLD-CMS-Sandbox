<?php

namespace SmartCore\Bundle\UnicatBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');

        $resources[] = 'UnicatBundle:Form:fields.html.twig';

        $container->setParameter('twig.form.resources', $resources);
    }
}
