<?php

namespace SmartCore\Module\Menu\Form\Tree;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use SmartCore\Module\Menu\Entity\Group;
use SmartCore\Module\Menu\Entity\Item;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;

class ItemLoader implements EntityLoaderInterface
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * @var array
     */
    protected $result;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var Group
     */
    protected $group;

    /**
     * @param ObjectManager $em
     * @param null $manager
     * @param null $class
     */
    public function __construct(ObjectManager $em, $manager = null, $class = null)
    {
        $this->repo  = $em->getRepository($class);
    }

    /**
     * @param Group $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Returns an array of entities that are valid choices in the corresponding choice list.
     *
     * @return array The entities.
     */
    public function getEntities()
    {
        $this->result = [];
        $this->level = 0;

        $this->addChild();

        return $this->result;
    }

    protected function addChild($parent = null)
    {
        $level = $this->level;
        $ident = '';
        while ($level--) {
            $ident .= '&nbsp;&nbsp;';
        }

        $this->level++;

        $items = $this->repo->findBy([
                'parent_item' => $parent,
                'group' => $this->group,
            ],
            ['position' => 'ASC']
        );

        /** @var $item Item */
        foreach ($items as $item) {
            if (null === $item->getFolder() or !$item->getFolder()->isActive()) {
//                $item->setFormTitle($ident.'<span style="text-decoration: line-through;">'.$item.'</span>');
                $item->setFormTitle($ident.'<b>'.$item.'</b>');
            } else {
                $item->setFormTitle($ident.$item);
            }
            $this->result[] = $item;
            $this->addChild($item);
        }

        $this->level--;
    }

    /**
     * Returns an array of entities matching the given identifiers.
     *
     * @param string $identifier The identifier field of the object. This method
     *                           is not applicable for fields with multiple
     *                           identifiers.
     * @param array $values The values of the identifiers.
     *
     * @return array The entities.
     */
    public function getEntitiesByIds($identifier, array $values)
    {
        return $this->repo->findBy(
            [$identifier => $values]
        );
    }
}
