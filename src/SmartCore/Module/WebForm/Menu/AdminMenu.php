<?php

namespace SmartCore\Module\WebForm\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use SmartCore\Module\WebForm\Entity\Message;
use SmartCore\Module\WebForm\Entity\WebForm;
use Symfony\Component\DependencyInjection\ContainerAware;

class AdminMenu extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     *
     * @return ItemInterface
     */
    public function manage(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('web_form_admin');

        $menu->setChildrenAttribute('class', isset($options['class']) ? $options['class'] : 'nav nav-tabs');

        /** @var WebForm $webForm */
        $webForm = $options['web_form'];

        $em = $this->container->get('doctrine.orm.entity_manager');

        $countNewMessages = $em->getRepository('WebFormModule:Message')->getCountByStatus($webForm, Message::STATUS_NEW);
        $countInProgress  = $em->getRepository('WebFormModule:Message')->getCountByStatus($webForm, Message::STATUS_IN_PROGRESS);

        $menu->addChild('New messages', ['route' => 'web_form.admin_new_messages',  'routeParameters' => ['name' => $webForm->getName() ]])->setExtras(['countNewMessages' => $countNewMessages]);
        $menu->addChild('In progress',  ['route' => 'web_form.admin_in_progress',   'routeParameters' => ['name' => $webForm->getName() ]])->setExtras(['countInProgress'  => $countInProgress]);
        $menu->addChild('Finished',     ['route' => 'web_form.admin_finished',      'routeParameters' => ['name' => $webForm->getName() ]]);
        $menu->addChild('Rejected',     ['route' => 'web_form.admin_rejected',      'routeParameters' => ['name' => $webForm->getName() ]]);
        $menu->addChild('Fields',       ['route' => 'web_form.admin_fields',        'routeParameters' => ['name' => $webForm->getName() ]]);
        $menu->addChild('Settings',     ['route' => 'web_form.admin_settings',      'routeParameters' => ['name' => $webForm->getName() ]]);

        return $menu;
    }
}
