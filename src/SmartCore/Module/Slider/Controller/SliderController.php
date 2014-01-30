<?php

namespace SmartCore\Module\Slider\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SliderController extends Controller
{
    use NodeTrait;

    public function indexAction()
    {
        return $this->render('SliderModule::slider.html.twig', [
            'slides' => null,
        ]);
    }
}
