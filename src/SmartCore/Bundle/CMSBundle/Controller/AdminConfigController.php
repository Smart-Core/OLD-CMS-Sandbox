<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\SettingsBundle\Form\Type\SettingFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminConfigController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('CMSBundle:AdminConfig:index.html.twig', [
            'settings' => $this->get('settings')->all(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editSettingAction(Request $request, $id)
    {
        $setting = $this->get('settings')->findById($id);

        if (empty($setting)) {
            return $this->redirectToRoute('cms_admin_config_media');
        }

        $form = $this->createForm(new SettingFormType(), $setting);
        $form->add('update', 'submit', ['attr' => ['class' => 'btn btn-success']]);
        $form->add('cancel', 'submit', ['attr' => ['class' => 'btn', 'formnovalidate' => 'formnovalidate']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('cms_admin_config');
            }

            if ($form->isValid()) {
                $this->get('settings')->updateEntity($form->getData());
                $this->addFlash('success', 'Настройка обновлена');

                return $this->redirectToRoute('cms_admin_config');
            }
        }

        return $this->render('CMSBundle:AdminConfig:edit_setting.html.twig', [
            'form' => $form->createView(),
            'setting' => $setting,
        ]);
    }
}
