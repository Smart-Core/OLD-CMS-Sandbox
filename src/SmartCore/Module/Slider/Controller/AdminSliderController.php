<?php

namespace SmartCore\Module\Slider\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminSliderController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $sliderService = $this->get('smart_module.slider');

        $form = $this->createForm('smart_module_slider');
        $form->add('create', 'submit', ['attr' => ['class' => 'btn btn-success']]);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $sliderService->createSlider($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Слайдер создан');

                return $this->redirect($this->generateUrl('smart_module.slider.admin'));
            }
        }

        return $this->render('SliderModule:Admin:index.html.twig', [
            'form'    => $form->createView(),
            'sliders' => $sliderService->allSliders(),
        ]);
    }

    /**
     * @param  Request $request
     * @param  int $slider_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \RuntimeException
     */
    public function sliderAction(Request $request, $id)
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

        return $this->render('SliderModule:Admin:slider.html.twig', [
            'form'    => $form->createView(),
            'slides'  => $sliderService->all(),
            'slider'  => $sliderService->getSlider($id),
            'webPath' => $sliderService->getWebPath(),
        ]);
    }

    /**
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sliderEditAction(Request $request, $id)
    {
        $sliderService = $this->get('smart_module.slider');

        $slider = $sliderService->getSlider($id);

        $form = $this->createForm('smart_module_slider', $slider);
        $form->add('update', 'submit', ['attr' => ['class' => 'btn btn-success']]);
        $form->add('delete', 'submit', ['attr' => ['class' => 'btn btn-danger']]);
        $form->add('cancel', 'submit', ['attr' => ['class' => 'btn']]);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('smart_module.slider.admin'));
            }

            if ($form->get('delete')->isClicked()) {
                $sliderService->deleteSlider($form->getData());

                $this->get('session')->getFlashBag()->add('success', 'Слайдер удалён');

                return $this->redirect($this->generateUrl('smart_module.slider.admin'));
            }

            if ($form->isValid() and $form->get('update')->isClicked()) {
                $sliderService->updateSlider($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Слайдер обновлён');

                return $this->redirect($this->generateUrl('smart_module.slider.admin'));
            }
        }

        return $this->render('SliderModule:Admin:slider_edit.html.twig', [
            'form'    => $form->createView(),
            'slider'  => $slider,
        ]);
    }
}
