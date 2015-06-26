<?php

namespace SmartCore\Module\Blog\Controller;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    use NodeTrait;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $this->node->addFrontControl('edit')
            ->setTitle('Редактировать тэги')
            ->setUri($this->generateUrl('smart_blog_admin_tag'))
            ->setIsDefault(true);

        return $this->render('BlogModule:Tag:index.html.twig', [
            'cloud' => $this->getTagService()->getCloud('smart_blog_tag'),
        ]);
    }

    /**
     * @param Request $requst
     * @param string $slug
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showArticlesAction(Request $requst, $slug)
    {
        $tagService = $this->getTagService();
        $tag        = $tagService->getBySlug($slug);

        if (null === $tag) {
            throw $this->createNotFoundException('Запрошенного тега не существует.');
        }

        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($tagService->getFindByTagQuery($tag)));
        $pagerfanta->setMaxPerPage($tagService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($requst->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_blog_tag_index'));
        }

        $this->node->addFrontControl('edit')
            ->setTitle('Редактировать тэги')
            ->setUri($this->generateUrl('smart_blog_admin_tag'));

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
