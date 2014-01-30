<?php

namespace SmartCore\Module\Slider\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminSliderController extends Controller
{
    public function indexAction(Request $request)
    {
        $usrDir = $this->container->getParameter('kernel.root_dir') . '/usr';

        $sliderOriginalDir = '/SmartSliderModule';

        $usrDir .= $sliderOriginalDir;

        if (!is_dir($usrDir) and false === @mkdir($usrDir, 0777, true)) {
            throw new \RuntimeException(sprintf("Unable to create the %s directory (%s)\n", $sliderOriginalDir, $usrDir));
        }

        //ld(realpath($usrDir));

        // -------------
        $form = $this->createForm('smart_module_slider_item');
        $form->add('upload', 'submit', ['attr' => ['class' => 'btn btn-success']]);

        $sliderService = $this->get('smart_module.slider');

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $sliderService->save($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Слайд создан');

                return $this->redirect($this->generateUrl('smart_module.slider.admin'));
            }
        }

        return $this->render('SliderModule:Admin:index.html.twig', [
            'form'    => $form->createView(),
            'slides'  => $sliderService->all(),
            'webPath' => $sliderService->getWebPath(),
        ]);
    }
}
