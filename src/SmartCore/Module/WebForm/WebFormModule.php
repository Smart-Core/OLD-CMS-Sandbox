<?php

namespace SmartCore\Module\WebForm;

use SmartCore\Bundle\CMSBundle\Module\ModuleBundle;
use SmartCore\Module\WebForm\Entity\Message;

class WebFormModule extends ModuleBundle
{
    public function getRequiredParams()
    {
        return [
            'webform_id',
        ];
    }

    public function getNotifications()
    {
        $data = [];

        $em = $this->container->get('doctrine.orm.entity_manager');

        foreach ($em->getRepository('WebFormModule:WebForm')->findAll() as $webForm) {
            $count = $em->getRepository('WebFormModule:Message')->getCountByStatus($webForm, Message::STATUS_NEW);

            if ($count) {
                // @todo подумать над форматом уведомлений.
                $data[] = [
                    'title' => 'Новые сообщения в веб-форме: '.$webForm->getTitle(),
                    'descr' => '',
                    'count' => $count,
                    'badge' => 'important',
                    'icon'  => '',
                    'html'  => null,
                    'url'   => $this->container->get('router')->generate('web_form.admin_new_messages', ['name' => $webForm->getName()]),
                ];
            }
        }

        return $data;
    }
}
