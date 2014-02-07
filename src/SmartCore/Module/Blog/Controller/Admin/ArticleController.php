<?php

namespace SmartCore\Module\Blog\Controller\Admin;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SmartCore\Module\Blog\Pagerfanta\SimpleDoctrineORMAdapter;

class ArticleController extends Controller
{
    /**
     * Форма создания статьи.
     *
     * @var string
     */
    protected $articleCreateForm;

    /**
     * Форма редактирования статьи.
     *
     * @var string
     */
    protected $articleEditForm;

    /**
     * Имя сервиса по работе со статьями.
     *
     * @var string
     */
    protected $articleServiceName;

    /**
     * Маршрут на список статей.
     *
     * @var string
     */
    protected $routeAdminArticle;

    /**
     * Маршрут редактирования статьи.
     *
     * @var string
     */
    protected $routeAdminArticleEdit;

    /**
     * Имя бандла. Для перегрузки шаблонов.
     *
     * @var string
     */
    protected $bundleName;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bundleName            = 'SmartBlogBundle';

        $this->articleCreateForm     = 'smart_blog.article.create.form.type';
        $this->articleEditForm       = 'smart_blog.article.edit.form.type';
        $this->articleServiceName    = 'smart_blog.article';
        $this->routeAdminArticle     = 'smart_blog_admin_article';
        $this->routeAdminArticleEdit = 'smart_blog_admin_article_edit';
    }

    /**
     * @param Request $requst
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $requst)
    {
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get($this->articleServiceName);

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($articleService->getFindByCategoryQuery()));
        $pagerfanta->setMaxPerPage($articleService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($requst->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl($this->routeAdminArticle));
        }

        return $this->render($this->bundleName . ':Admin/Article:index.html.twig', [
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
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get($this->articleServiceName);
        $article        = $articleService->get($id);

        if (null === $article) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($this->get($this->articleEditForm), $article);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $articleService->update($form->getData());

                return $this->redirect($this->generateUrl($this->routeAdminArticle));
            }
        }

        return $this->render($this->bundleName . ':Admin/Article:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get($this->articleServiceName);
        $article        = $articleService->create();

        $form = $this->createForm($this->get($this->articleCreateForm), $article);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $articleService->update($form->getData(), false);

                return $this->redirect($this->generateUrl($this->routeAdminArticle));
            }
        }

        return $this->render($this->bundleName . ':Admin/Article:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
