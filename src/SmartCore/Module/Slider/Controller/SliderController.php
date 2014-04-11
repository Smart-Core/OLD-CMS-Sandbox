<?php

namespace SmartCore\Module\Slider\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Slider\Entity\Slider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SliderController extends Controller
{
    use NodeTrait;

    /**
     * @var int
     */
    protected $slider_id;

    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        if (null === $this->slider_id) {
            return new Response();
        }

        /** @var Slider $slider */
        $slider = $this->get('slidermodule.entity.slider_repository')->find($this->slider_id);

        return $this->render('SliderModule::'.$slider->getLibrary().'.html.twig', [
            'slider'  => $slider,
            // @todo настройку места хранения картинок, лучше в медиалибе!.
            'imgPath' => $request->getBasePath() . '/' . $this->get('smart_module.slider')->getWebPath(),
        ]);
    }
}
