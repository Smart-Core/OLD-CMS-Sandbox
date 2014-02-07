<?php

namespace SmartCore\Module\Blog\Controller;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Module\Blog\Pagerfanta\SimpleDoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $this->bundleName       = 'SmartBlogBundle';

        $this->tagServiceName   = 'smart_blog.tag';
        $this->routeIndex       = 'smart_blog_tag_index';
        $this->routeTag         = 'smart_blog_tag';
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render($this->bundleName . ':Tag:index.html.twig', [
            'cloud' => $this->getTagService()->getCloud($this->routeTag),
        ]);
    }

    /**
     * @param Request $requst
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showArticlesAction(Request $requst, $slug)
    {
        $tagService = $this->getTagService();
        $tag        = $tagService->getBySlug($slug);

        if (null === $tag) {
            throw $this->createNotFoundException('Запрошенного тега не существует.');
        }

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($tagService->getFindByTagQuery($tag)));
        $pagerfanta->setMaxPerPage($tagService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($requst->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl($this->routeIndex));
        }

        return $this->render($this->bundleName . ':Tag:show_articles.html.twig', [
            'pagerfanta' => $pagerfanta,
            'tag'        => $tag,
        ]);
    }

    /**
     * @return \SmartCore\Bundle\BlogBundle\Service\TagService
     */
    protected function getTagService()
    {
        return $this->get($this->tagServiceName);
    }
}
