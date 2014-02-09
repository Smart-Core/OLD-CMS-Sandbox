<?php

namespace SmartCore\Module\Slider\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SliderController extends Controller
{
    use NodeTrait;

    public function indexAction(Request $request)
    {
        return $this->render('SliderModule::slider.html.twig', [
            // @todo настройку ноды.
            'slider'  => $this->get('slidermodule.entity.slider_repository')->find(1),
            // @todo настройку места хранения картинок, лучше в медиалибе!.
            'imgPath' => $request->getBasePath() . '/' . $this->get('smart_module.slider')->getWebPath(),
        ]);
    }
}
