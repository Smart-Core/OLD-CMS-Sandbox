<?php

namespace SmartCore\Module\Slider;

use SmartCore\Bundle\CMSBundle\Module\ModuleBundle;

class SliderModule extends ModuleBundle
{
    /**
     * Получить виджеты для рабочего стола.
     *
     * @return array
     */
    public function getDashboard()
    {
        $data = [
            'title' => 'Слайдер',
            'items' => [],
        ];

        foreach ($this->container->get('smart_module.slider')->allSliders() as $slider) {
            $data['items']['edit_slider_'.$slider->getId()] = [
                'title' => 'Редактировать слайдер: <b>'.$slider->getTitle().'</b>',
                'descr' => '',
                'url' => $this->container->get('router')->generate('smart_module.slider.admin_slider', ['id' => $slider->getId()]),
            ];
        }

        return $data;
    }
}
