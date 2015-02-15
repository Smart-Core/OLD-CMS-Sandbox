<?php

namespace SmartCore\Module\SimpleNews;

use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Module\Bundle;
use SmartCore\Module\SimpleNews\DependencyInjection\Compiler\FormPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SimpleNewsModule extends Bundle
{
    /**
     * Действие при создании ноды.
     * @param Node $node
     */
    public function createNode(Node $node)
    {
        $node->setParams([
            'items_per_page' => 10,
        ]);
    }

    /**
     * Получить виджеты для рабочего стола.
     *
     * @return array
     */
    public function getDashboard()
    {
        $r = $this->container->get('router');

        $data = [
            'title' => 'Новости',
            'items' => [
                'new' => [
                    'title' => 'Написать новость',
                    'descr' => '',
                    'url' => $r->generate('smart_module.news_admin.create'),
                ],
            ],
        ];

        return $data;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormPass());
    }
}
