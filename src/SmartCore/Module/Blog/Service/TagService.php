<?php

namespace SmartCore\Module\Blog\Service;

use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SmartCore\Module\Blog\Event\FilterTagEvent;
use SmartCore\Module\Blog\Model\TagInterface;
use SmartCore\Module\Blog\Repository\ArticleRepositoryInterface;
use SmartCore\Module\Blog\SmartBlogEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Zend\Tag\Cloud;

class TagService extends AbstractBlogService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var \SmartCore\Module\Blog\Repository\TagRepository
     */
    protected $tagsRepo;

    /**
     * @param EntityManager $em
     * @param ArticleRepositoryInterface $articlesRepo
     * @param Cache $cache
     * @param EntityRepository $tagsRepo
     * @param EventDispatcherInterface $eventDispatcher
     * @param RouterInterface $router
     * @param int $itemsPerPage
     */
    public function __construct(
        EntityManager $em,
        ArticleRepositoryInterface $articlesRepo,
        Cache $cache,
        EntityRepository $tagsRepo,
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        $itemsPerPage = 10)
    {
        $this->articlesRepo     = $articlesRepo;
        $this->cache            = $cache;
        $this->em               = $em;
        $this->eventDispatcher  = $eventDispatcher;
        $this->router           = $router;
        $this->tagsRepo         = $tagsRepo;

        $this->setItemsCountPerPage($itemsPerPage);
    }

    /**
     * @return TagInterface
     */
    public function create()
    {
        $class = $this->tagsRepo->getClassName();

        $tag = new $class();

        $event = new FilterTagEvent($tag);
        $this->eventDispatcher->dispatch(SmartBlogEvents::TAG_CREATE, $event);

        return $tag;
    }

    /**
     * @param TagInterface $tag
     */
    public function update(TagInterface $tag)
    {
        $event = new FilterTagEvent($tag);
        $this->eventDispatcher->dispatch(SmartBlogEvents::TAG_PRE_UPDATE, $event);

        $this->em->persist($tag);
        $this->em->flush($tag);

        $event = new FilterTagEvent($tag);
        $this->eventDispatcher->dispatch(SmartBlogEvents::TAG_POST_UPDATE, $event);
    }

    /**
     * @param int $id
     *
     * @return TagInterface|null
     */
    public function get($id)
    {
        return $this->tagsRepo->find($id);
    }

    /**
     * @return TagInterface[]|null
     *
     * @throws \Exception
     *
     * @todo нормальный выброс исключения.
     */
    public function getAll()
    {
        if (null === $this->tagsRepo) {
            throw new \Exception('Необходимо сконфигурировать тэги.');
        }

        return $this->tagsRepo->findAll();
    }

    /**
     * @param TagInterface $tag
     *
     * @return int
     *
     * @todo возможность выбора по нескольким тэгам.
     */
    public function getArticlesCountByTag(TagInterface $tag = null)
    {
        return $this->tagsRepo->getCountByTag($tag);
    }

    /**
     * @param string $slug
     *
     * @return TagInterface
     *
     * @throws \Exception
     *
     * @todo нормальный выброс исключения.
     */
    public function getBySlug($slug)
    {
        if (null === $this->tagsRepo) {
            throw new \Exception('Необходимо сконфигурировать тэги.');
        }

        return $this->tagsRepo->findOneBy(['slug' => $slug]);
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getFindAllQuery()
    {
        return $this->tagsRepo->getFindAllQuery();
    }

    /**
     * @param TagInterface $tag
     *
     * @return \Doctrine\ORM\Query
     */
    public function getFindByTagQuery(TagInterface $tag)
    {
        return $this->articlesRepo->getFindByTagQuery($tag);
    }

    /**
     * @param string $route
     *
     * @return array
     *
     * @todo сделать в сущности тэга weight, который будет автоматически инкрементироваться и декрементироваться при измнениях в статьях.
     */
    public function getCloud($route)
    {
        $cloud = [];
        $tags = $this->getAll();

        /** @var TagInterface $tag */
        foreach ($tags as $tag) {
            $cloud[] = [
                'tag'    => $tag,
                'title'  => $tag->getTitle(),
                'weight' => $this->getArticlesCountByTag($tag),
                'params' => [
                    'url' => $this->router->generate($route, ['slug' => $tag->getSlug()]),
                ],
            ];
        }

        return $cloud;
    }

    /**
     * @param string $route
     *
     * @return Cloud
     */
    public function getCloudZend($route)
    {
        return new Cloud([
            'tags' => $this->getCloud($route),
            'cloudDecorator' => [
                'decorator' => 'HtmlCloud',
                'options' => [
                    'htmlTags' => [
                        'div' => ['id' => 'tags'],
                    ],
                    'separator' => ' ',
                ],
            ],
            'tagDecorator' => [
                'decorator' => 'HtmlTag',
                'options' => [
                    'htmlTags' => ['span'],
                ],
            ],
        ]);
    }
}
