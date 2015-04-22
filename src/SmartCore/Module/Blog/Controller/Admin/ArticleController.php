<?php

namespace SmartCore\Module\Blog\Controller\Admin;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Smart\CoreBundle\Pagerfanta\SimpleDoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @param Request $requst
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $requst)
    {
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get('smart_blog.article');

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($articleService->getFindByCategoryQuery()));
        $pagerfanta->setMaxPerPage($articleService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($requst->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_blog_admin_article'));
        }

        return $this->render('BlogModule:Admin/Article:index.html.twig', [
            'pagerfanta' => $pagerfanta,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $id)
    {
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get('smart_blog.article');
        $article        = $articleService->get($id);

        if (null === $article) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($this->get('smart_blog.article.edit.form.type'), $article);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $articleService->update($form->getData());

                return $this->redirect($this->generateUrl('smart_blog_admin_article'));
            }
        }

        return $this->render('BlogModule:Admin/Article:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get('smart_blog.article');
        $article        = $articleService->create();

        $form = $this->createForm($this->get('smart_blog.article.create.form.type'), $article);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $articleService->update($form->getData(), false);

                return $this->redirect($this->generateUrl('smart_blog_admin_article'));
            }
        }

        return $this->render('BlogModule:Admin/Article:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
