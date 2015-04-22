<?php

namespace SmartCore\Module\Blog\Service;

use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SmartCore\Module\Blog\Event\FilterCategoryEvent;
use SmartCore\Module\Blog\Model\CategoryInterface;
use SmartCore\Module\Blog\SmartBlogEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;

class CategoryService extends AbstractBlogService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var EntityRepository
     */
    protected $categoriesRepo;

    /**
     * @param EntityManager $em
     * @param RouterInterface $router
     * @param int $itemsPerPage
     */
    public function __construct(
        EntityManager $em,
        EntityRepository $categoriesRepo,
        Cache $cache,
        EventDispatcherInterface $eventDispatcher,
        $itemsPerPage = 10)
    {
        $this->cache            = $cache;
        $this->categoriesRepo   = $categoriesRepo;
        $this->em               = $em;
        $this->eventDispatcher  = $eventDispatcher;

        $this->setItemsCountPerPage($itemsPerPage);
    }

    /**
     * @return CategoryInterface
     */
    public function create()
    {
        $class = $this->categoriesRepo->getClassName();

        $category = new $class();

        $event = new FilterCategoryEvent($category);
        $this->eventDispatcher->dispatch(SmartBlogEvents::CATEGORY_CREATE, $event);

        return $category;
    }

    /**
     * @param CategoryInterface $category
     */
    public function update(CategoryInterface $category)
    {
        $event = new FilterCategoryEvent($category);
        $this->eventDispatcher->dispatch(SmartBlogEvents::CATEGORY_PRE_UPDATE, $event);

        $this->em->persist($category);
        $this->em->flush($category);

        $event = new FilterCategoryEvent($category);
        $this->eventDispatcher->dispatch(SmartBlogEvents::CATEGORY_POST_UPDATE, $event);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return CategoryInterface
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->categoriesRepo->findOneBy($criteria, $orderBy);
    }

    /**
     * @param int $id
     *
     * @return CategoryInterface|null
     */
    public function get($id)
    {
        return $this->categoriesRepo->find($id);
    }

    /**
     * @return \Doctrine\Common\Cache\Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return string
     */
    public function getCategoryClass()
    {
        return $this->categoriesRepo->getClassName();
    }

    /**
     * @return CategoryInterface[]|null
     */
    public function all()
    {
        return $this->categoriesRepo->findAll();
    }

    /**
     * Получить корневые категории.
     *
     * @return CategoryInterface[]|null
     */
    public function getRoots()
    {
        return $this->categoriesRepo->findBy(['parent' => null]);
    }
}
