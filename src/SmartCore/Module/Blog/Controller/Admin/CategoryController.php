<?php

namespace SmartCore\Module\Blog\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $categoryService = $this->getCategoryService();
        $category        = $categoryService->create();

        $form = $this->createForm($this->get('smart_blog.category.create.form.type'), $category);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $categoryService->update($category);

                return $this->redirect($this->generateUrl('smart_blog_admin_category'));
            }
        }

        return $this->render('BlogModule:Admin/Category:index.html.twig', [
            'categoryClass' => get_class($category),
            'form'          => $form->createView(),
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
        $categoryService = $this->getCategoryService();
        $category        = $categoryService->get($id);

        if (null === $category) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($this->get('smart_blog.category.edit.form.type'), $category);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $categoryService->update($category);

                return $this->redirect($this->generateUrl('smart_blog_admin_category'));
            }
        }

        return $this->render('BlogModule:Admin/Category:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \SmartCore\Module\Blog\Service\CategoryService
     */
    protected function getCategoryService()
    {
        return $this->get('smart_blog.category');
    }
}
