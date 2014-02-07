<?php

namespace SmartCore\Module\Blog\Controller\Admin;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Module\Blog\Pagerfanta\SimpleDoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zend\Tag\Cloud;

class TagController extends Controller
{
    /**
     * Имя бандла. Для перегрузки шаблонов.
     *
     * @var string
     */
    protected $bundleName;

    /**
     * Маршрут на список тэгов.
     *
     * @var string
     */
    protected $routeIndex;

    /**
     * Маршрут просмотра списка статей по тэгу.
     *
     * @var string
     */
    protected $routeTag;

    /**
     * Форма создания тэга.
     *
     * @var string
     */
    protected $tagCreateForm;

    /**
     * Форма редактирования тэга.
     *
     * @var string
     */
    protected $tagEditForm;

    /**
     * Имя сервиса по работе с тэгами.
     *
     * @var string
     */
    protected $tagServiceName;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->bundleName        = 'BlogModule';

        $this->tagCreateForm     = 'smart_blog.tag.create.form.type';
        $this->tagEditForm       = 'smart_blog.tag.edit.form.type';
        $this->tagServiceName    = 'smart_blog.tag';
        $this->routeIndex        = 'smart_blog_tag_index';
        $this->routeAdminTag     = 'smart_blog_admin_tag';
        $this->routeAdminTagEdit = 'smart_blog_admin_tag_edit';
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $tagService = $this->get($this->tagServiceName);
        $tag        = $tagService->create();

        $form = $this->createForm($this->get($this->tagCreateForm), $tag);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tagService->update($tag);

                return $this->redirect($this->generateUrl($this->routeAdminTag));
            }
        }

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($tagService->getFindAllQuery()));
        $pagerfanta->setMaxPerPage($tagService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($request->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl($this->routeAdminTag));
        }

        return $this->render($this->bundleName . ':Admin/Tag:index.html.twig', [
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
        $tagService = $this->get($this->tagServiceName);
        $tag        = $tagService->get($id);

        if (null === $tag) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($this->get($this->tagEditForm), $tag);
        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $tagService->update($tag);

                return $this->redirect($this->generateUrl($this->routeAdminTag));
            }
        }

        return $this->render($this->bundleName . ':Admin/Tag:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \SmartCore\Module\Blog\Service\TagService
     */
    protected function getTagService()
    {
        return $this->get($this->tagServiceName);
    }
}
