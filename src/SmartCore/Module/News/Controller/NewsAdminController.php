<?php

namespace SmartCore\Module\News\Controller;

use SmartCore\Bundle\CMSBundle\Response;
use SmartCore\Bundle\CMSBundle\Module\Controller;
use SmartCore\Module\News\Form\Type\NewsFormType;
use Symfony\Component\HttpFoundation\Request;

class NewsAdminController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine();

        return $this->render('NewsModule:Admin:index.html.twig', [
            'news' => $em->getRepository('NewsModule:News')->findAll(),
        ]);
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(new NewsFormType());
        $form->add('create', 'submit', ['label' => 'Создать', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $item = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($item);
                $em->flush($item);

                $this->get('session')->getFlashBag()->add('notice', 'Новость создана.'); // @todo translate
                return $this->redirect($this->generateUrl('smart_news_admin'));
            }
        }
        return $this->render('NewsModule:Admin:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new NewsFormType(), $em->find('NewsModule:News', $id));
        $form->add('update', 'submit', ['label' => 'Сохранить', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $item = $form->getData();
                $em->persist($item);
                $em->flush($item);

                $this->get('session')->getFlashBag()->add('notice', 'Новость сохранена.'); // @todo translate
                return $this->redirect($this->generateUrl('smart_news_admin'));
            }
        }

        return $this->render('NewsModule:Admin:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
