<?php

namespace SmartCore\Module\Slider;

use SmartCore\Bundle\CMSBundle\Module\Bundle;

class SliderModule extends Bundle
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

        $data = [
            'title' => 'Слайдер',
            'items' => [],
        ];

        foreach ($this->container->get('smart_module.slider')->allSliders() as $slider) {
            $data['items']['edit_slider_'.$slider->getId()] = [
                'title' => 'Редактировать слайдер: <b>'.$slider->getTitle().'</b>',
                'descr' => '',
                'url' => $r->generate('smart_module.slider.admin_slider', ['id' => $slider->getId()]),
            ];
        }

        return $data;
    }
}
