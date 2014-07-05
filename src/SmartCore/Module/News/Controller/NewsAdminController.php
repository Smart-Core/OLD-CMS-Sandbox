<?php

namespace SmartCore\Module\News\Controller;

use SmartCore\Module\News\Entity\News;
use SmartCore\Module\News\Form\Type\NewsFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NewsAdminController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine();

        return $this->render('NewsModule:Admin:index.html.twig', [
            'news' => $em->getRepository('NewsModule:News')->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new NewsFormType(), new News());
        $form->add('create', 'submit', ['attr' => ['class' => 'btn btn-success']]);
        $form->add('cancel', 'submit', ['attr' => ['class' => 'btn', 'formnovalidate' => 'formnovalidate']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                $url = $request->query->has('redirect_to')
                    ? $request->query->get('redirect_to')
                    : $this->generateUrl('smart_module.news_admin');

                return $this->redirect($url);
            }

            if ($form->isValid()) {
                return $this->saveItemAndRedirect($request, $form->getData(), 'smart_module.news_admin', 'Новость создана.');
            }
        }

        return $this->render('NewsModule:Admin:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $form = $this->createForm(new NewsFormType(), $this->getDoctrine()->getManager()->find('NewsModule:News', $id));
        $form->add('update', 'submit', ['attr' => ['class' => 'btn btn-success']]);
        $form->add('delete', 'submit', ['attr' => ['class' => 'btn btn-danger', 'onclick' => "return confirm('Вы уверены, что хотите удалить запись?')"]]);
        $form->add('cancel', 'submit', ['attr' => ['class' => 'btn', 'formnovalidate' => 'formnovalidate']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                $url = $request->query->has('redirect_to')
                    ? $request->query->get('redirect_to')
                    : $this->generateUrl('smart_module.news_admin');

                return $this->redirect($url);
            }

            if ($form->get('delete')->isClicked()) {
                $item = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->remove($item);
                $em->flush($item);

                $this->get('session')->getFlashBag()->add('success', 'Запись удалена');

                return $this->redirect($this->generateUrl('smart_module.news_admin'));
            }

            if ($form->isValid()) {
                return $this->saveItemAndRedirect($request, $form->getData(), 'smart_module.news_admin', 'Новость сохранена.');
            }
        }

        return $this->render('NewsModule:Admin:edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Сохранение записи.
     *
     * @param int           $item
     * @param string        $redirect_to
     * @param string|null   $notice
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function saveItemAndRedirect(Request $request, $item, $redirect_to, $notice = null)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush($item);

        $this->get('session')->getFlashBag()->add('success', $notice);

        $url = $request->query->has('redirect_to')
            ? $request->query->get('redirect_to')
            : $this->generateUrl($redirect_to);

        return $this->redirect($url);
    }
}
