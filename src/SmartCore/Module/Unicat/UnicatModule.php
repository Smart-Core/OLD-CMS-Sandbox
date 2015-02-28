<?php

namespace SmartCore\Module\Unicat;

use SmartCore\Bundle\CMSBundle\Module\ModuleBundleTrait;
use SmartCore\Module\Unicat\DependencyInjection\Compiler\FormPass;
use SmartCore\Module\Unicat\DependencyInjection\UnicatExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UnicatModule extends Bundle
{
    use ModuleBundleTrait;

    /**
     * Получить виджеты для рабочего стола.
     *
     * @return array
     */
    public function getDashboard()
    {
        $em      = $this->container->get('doctrine.orm.default_entity_manager');
        $r       = $this->container->get('router');
        $configs = $em->getRepository('UnicatModule:UnicatConfiguration')->findAll();

        $data = [
            'title' => 'Юникат',
            'items' => [],
        ];

        foreach ($configs as $config) {
            $data['items']['manage_config_'.$config->getId()] = [
                'title' => 'Конфигурация: <b>'.$config->getTitle().'</b>',
                'descr' => '',
                'url' => $r->generate('smart_module.unicat_admin.configuration', ['configuration' => $config->getName()]),
            ];
        }

        return $data;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormPass());
    }

    public function getContainerExtension()
    {
        return new UnicatExtension();
    }
}
