<?php

namespace SmartCore\Module\Blog\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Имя бандла. Для перегрузки шаблонов.
     *
     * @var string
     */
    protected $bundleName;

    /**
     * Маршрут на список категорий.
     *
     * @var string
     */
    protected $routeIndex;

    /**
     * Маршрут просмотра списка категорий.
     *
     * @var string
     */
    protected $routeCategory;

    /**
     * Форма создания категории.
     *
     * @var string
     */
    protected $categoryCreateForm;

    /**
     * Форма редактирования категории.
     *
     * @var string
     */
    protected $categoryEditForm;

    /**
     * Имя сервиса по работе с категориями.
     *
     * @var string
     */
    protected $categoryServiceName;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bundleName             = 'SmartBlogBundle';

        $this->categoryCreateForm     = 'smart_blog.category.create.form.type';
        $this->categoryEditForm       = 'smart_blog.category.edit.form.type';
        $this->categoryServiceName    = 'smart_blog.category';
        $this->routeIndex             = 'smart_blog.category.articles';
        $this->routeAdminCategory     = 'smart_blog_admin_category';
        $this->routeAdminCategoryEdit = 'smart_blog_admin_category_edit';
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $categoryService = $this->getCategoryService();
        $category        = $categoryService->create();

        $form = $this->createForm($this->get($this->categoryCreateForm), $category);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $categoryService->update($category);

                return $this->redirect($this->generateUrl($this->routeAdminCategory));
            }
        }

        return $this->render($this->bundleName . ':Admin/Category:index.html.twig', [
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

        $form = $this->createForm($this->get($this->categoryEditForm), $category);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $categoryService->update($category);

                return $this->redirect($this->generateUrl($this->routeAdminCategory));
            }
        }

        return $this->render($this->bundleName . ':Admin/Category:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \SmartCore\Module\Blog\Service\CategoryService
     */
    protected function getCategoryService()
    {
        return $this->get($this->categoryServiceName);
    }
}
