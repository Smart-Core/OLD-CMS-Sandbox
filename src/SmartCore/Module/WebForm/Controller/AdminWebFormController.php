<?php

namespace SmartCore\Module\WebForm\Controller;

use Knp\RadBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SmartCore\Module\WebForm\Entity\WebForm;
use SmartCore\Module\WebForm\Entity\WebFormField;
use SmartCore\Module\WebForm\Form\Type\WebFormFieldType;
use SmartCore\Module\WebForm\Form\Type\WebFormType;
use Symfony\Component\HttpFoundation\Request;

class AdminWebFormController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new WebFormType());
        $form->add('create', 'submit', ['attr' => ['class' => 'btn-primary']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($form->get('create')->isClicked()) {
                    $webForm = $form->getData();
                    $webForm->setUserId($this->getUser());
                    $this->addFlash('success', 'Веб-форма создана.');

                    $this->persist($webForm, true);
                }

                return $this->redirect($this->generateUrl('web_form.admin'));
            }
        }

        return $this->render('WebFormModule:Admin:index.html.twig', [
            'form' => $form->createView(),
            'web_forms' => $em = $this->getDoctrine()->getManager()->getRepository('WebFormModule:WebForm')->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param WebForm $webForm
     *
     * ParamConverter("webForm", options={"mapping": {"name": "name"}})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fieldsAction(Request $request, WebForm $webForm)
    {
        $webFormField = new WebFormField();
        $webFormField
            ->setWebForm($webForm)
            ->setUserId($this->getUser())
        ;

        $form = $this->createForm(new WebFormFieldType(), $webFormField);
        $form->add('create', 'submit', ['attr' => ['class' => 'btn-primary']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($form->get('create')->isClicked()) {
                    $this->addFlash('success', 'Поле создано.');

                    $this->persist($form->getData(), true);
                }

                return $this->redirect($this->generateUrl('web_form.admin_fields', ['name' => $webForm->getName()]));
            }
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        return $this->render('WebFormModule:Admin:fields.html.twig', [
            'web_form' => $webForm,
            'web_form_fields' => $em->getRepository('WebFormModule:WebFormField')->findBy(['web_form' => $webForm]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param WebForm $webForm
     * @param WebFormField $webFormField
     *
     * @ParamConverter("webForm", options={"mapping": {"name": "name"}})
     * @ParamConverter("webFormField", options={"mapping": {"id": "id"}})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fieldEditAction(Request $request, WebForm $webForm, WebFormField $webFormField)
    {
        $form = $this->createForm(new WebFormFieldType(), $webFormField);
        $form
            ->add('update', 'submit', ['attr' => ['class' => 'btn-primary']])
            ->add('delete', 'submit', ['attr' => [ 'class' => 'btn btn-danger', 'onclick' => "return confirm('Вы уверены, что хотите удалить поле?')" ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]])
        ;

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('web_form.admin_fields', ['name' => $webForm->getName()]));
            }

            if ($form->get('delete')->isClicked()) {
                $this->remove($form->getData(), true);
                $this->get('session')->getFlashBag()->add('success', 'Поле удалено.');

                return $this->redirect($this->generateUrl('web_form.admin_fields', ['name' => $webForm->getName()]));
            }

            if ($form->isValid() and $form->get('update')->isClicked() and $form->isValid()) {
                $this->persist($form->getData(), true);
                $this->get('session')->getFlashBag()->add('success', 'Поле обновлено.');

                return $this->redirect($this->generateUrl('web_form.admin_fields', ['name' => $webForm->getName()]));
            }
        }

        return $this->render('WebFormModule:Admin:field_edit.html.twig', [
            'web_form' => $webForm,
            'web_form_field' => $webFormField,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @param Request $request
     * @param WebForm $webForm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manageAction(Request $request, WebForm $webForm)
    {
        return $this->render('WebFormModule:Admin:manage.html.twig', [
            'web_form' => $webForm,
            //'form' => $form->createView(),
        ]);
    }
}
