<?php

namespace SmartCore\Module\Menu;

use SmartCore\Bundle\CMSBundle\Module\ModuleBundle;
use SmartCore\Module\Menu\DependencyInjection\Compiler\FormPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MenuModule extends ModuleBundle
{
    /**
     * Получить виджеты для рабочего стола.
     *
     * @return array
     */
    public function getDashboard()
    {
        $em     = $this->container->get('doctrine.orm.default_entity_manager');
        $r      = $this->container->get('router');
        $groups = $em->getRepository('MenuModule:Group')->findAll();

        $data = [
            'title' => 'Меню',
            'items' => [],
        ];

        foreach ($groups as $group) {
            $data['items']['edit_group_'.$group->getId()] = [
                'title' => 'Редактировать меню: <b>'.$group->getName().'</b>',
                'descr' => '',
                'url' => $r->generate('smart_menu_admin_group', ['group_id' => $group->getId()]),
            ];
        }

        return $data;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormPass());
    }
}
