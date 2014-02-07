<?php

namespace SmartCore\Module\Blog\Controller;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Bundle\CMSBundle\Pagerfanta\SimpleDoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('BlogModule:Tag:index.html.twig', [
            'cloud' => $this->getTagService()->getCloud('smart_blog_tag'),
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
            return $this->redirect($this->generateUrl('smart_blog_tag_index'));
        }

        return $this->render('BlogModule:Tag:show_articles.html.twig', [
            'pagerfanta' => $pagerfanta,
            'tag'        => $tag,
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
