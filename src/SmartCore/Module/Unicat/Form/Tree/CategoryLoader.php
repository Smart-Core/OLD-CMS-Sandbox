<?php

namespace SmartCore\Module\Unicat\Form\Tree;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use SmartCore\Module\Unicat\Entity\UnicatStructure;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;

class CategoryLoader implements EntityLoaderInterface
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
     * @var UnicatStructure
     */
    protected $structure;

    /**
     * @param ObjectManager $em
     * @param null $manager
     * @param null $class
     */
    public function __construct(ObjectManager $em, UnicatStructure $structure, $class = null)
    {
        $this->repo = $em->getRepository($class);
        $this->structure = $structure;
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

        $folders = $this->repo->findBy(
            ['parent' => $parent, 'structure' => $this->structure],
            ['position' => 'ASC']
        );

        /** @var $folder Folder */
        foreach ($folders as $folder) {
            $folder->setFormTitle($ident.$folder->getTitle());
            $this->result[] = $folder;
            $this->addChild($folder);
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
        return $this->repo->findBy([$identifier => $values]);
    }
}
