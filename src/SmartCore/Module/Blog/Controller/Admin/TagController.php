<?php

namespace SmartCore\Module\Blog\Controller\Admin;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Bundle\CMSBundle\Pagerfanta\SimpleDoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $tagService = $this->get('smart_blog.tag');
        $tag        = $tagService->create();

        $form = $this->createForm($this->get('smart_blog.tag.create.form.type'), $tag);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tagService->update($tag);

                return $this->redirect($this->generateUrl('smart_blog_admin_tag'));
            }
        }

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($tagService->getFindAllQuery()));
        $pagerfanta->setMaxPerPage($tagService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($request->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_blog_admin_tag'));
        }

        return $this->render('BlogModule:Admin/Tag:index.html.twig', [
            'form'       => $form->createView(),
            'pagerfanta' => $pagerfanta,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $id)
    {
        $tagService = $this->get('smart_blog.tag');
        $tag        = $tagService->get($id);

        if (null === $tag) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($this->get('smart_blog.tag.edit.form.type'), $tag);
        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $tagService->update($tag);

                return $this->redirect($this->generateUrl('smart_blog_admin_tag'));
            }
        }

        return $this->render('BlogModule:Admin/Tag:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \SmartCore\Module\Blog\Service\TagService
     */
    protected function getTagService()
    {
        return $this->get('smart_blog.tag');
    }
}
