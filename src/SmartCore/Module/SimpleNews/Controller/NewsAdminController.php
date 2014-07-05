<?php

namespace SmartCore\Module\SimpleNews\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Module\SimpleNews\Entity\News;
use SmartCore\Module\SimpleNews\Entity\NewsInstance;
use SmartCore\Module\SimpleNews\Form\Type\NewsFormType;
use Symfony\Component\HttpFoundation\Request;

class NewsAdminController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine();

        return $this->render('SimpleNewsModule:Admin:index.html.twig', [
            'news' => $em->getRepository('SimpleNewsModule:News')->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // @todo пока временная заглушка для экземпляров новостных лент.
        $newsInstance = $em->getRepository('SimpleNewsModule:NewsInstance')->findOneBy([]);

        if (empty($newsInstance)) {
            $newsInstance = new NewsInstance();
            $newsInstance->setName('Default news');
        }

        $news = new News();
        $news->setInstance($newsInstance);

        $form = $this->createForm(new NewsFormType(), $news);
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

        return $this->render('SimpleNewsModule:Admin:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param  Request $request
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $form = $this->createForm(new NewsFormType(), $this->getDoctrine()->getManager()->find('SimpleNewsModule:News', $id));
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
                $this->remove($form->getData(), true);
                $this->addFlash('success', 'Запись удалена');

                return $this->redirectToRoute('smart_module.news_admin');
            }

            if ($form->isValid()) {
                return $this->saveItemAndRedirect($request, $form->getData(), 'smart_module.news_admin', 'Новость сохранена.');
            }
        }

        return $this->render('SimpleNewsModule:Admin:edit.html.twig', ['form' => $form->createView()]);
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
        $this->persist($item, true);

        $this->get('session')->getFlashBag()->add('success', $notice);

        $url = $request->query->has('redirect_to')
            ? $request->query->get('redirect_to')
            : $this->generateUrl($redirect_to);

        return $this->redirect($url);
    }
}
