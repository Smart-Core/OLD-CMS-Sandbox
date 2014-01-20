<?php

namespace SmartCore\Module\News\Controller;

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
                return $this->saveItem($form->getData(), 'smart_news_admin', 'Новость создана.');
            }
        }
        return $this->render('NewsModule:Admin:create.html.twig', ['form' => $form->createView()]);
    }

    public function editAction(Request $request, $id)
    {
        $form = $this->createForm(new NewsFormType(), $this->getDoctrine()->getManager()->find('NewsModule:News', $id));
        $form->add('update', 'submit', [
            'label' => 'Сохранить',
            'attr' => ['class' => 'btn btn-primary'],
        ]);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                return $this->saveItem($form->getData(), 'smart_news_admin', 'Новость сохранена.');
            }
        }

        return $this->render('NewsModule:Admin:edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Сохранение записи.
     *
     * @param int           $item
     * @param string        $redirect_to
     * @param string|null   $flash_notice
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function saveItem($item, $redirect_to, $flash_notice = null)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush($item);

        $this->get('session')->getFlashBag()->add('notice', $flash_notice);

        return $this->redirect($this->generateUrl($redirect_to));
    }
}
