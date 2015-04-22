<?php
namespace SmartCore\Bundle\CMSBundle\Form\Tree;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use SmartCore\Bundle\CMSBundle\Entity\Folder;

class FolderLoader implements EntityLoaderInterface
{
    /** @var \Doctrine\ORM\EntityRepository */
    private $repo;

    /** @var array */
    protected $result;

    /** @var int */
    protected $level;

    /** @var bool */
    protected $only_active = false;

    /**
     * @param ObjectManager $em
     * @param null $manager
     * @param string|null $class
     */
    public function __construct(ObjectManager $em, $manager = null, $class = null)
    {
        $this->repo = $em->getRepository($class);
    }

    /**
     * @param bool|null $only_active
     *
     * @return $this
     */
    public function setOnlyActive($only_active)
    {
        $this->only_active = $only_active;

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

    /**
     * @param Folder|null $parent_folder
     */
    protected function addChild($parent_folder = null)
    {
        $level = $this->level;
        $ident = '';
        while ($level--) {
            $ident .= '&nbsp;&nbsp;';
        }

        $this->level++;

        $criteria = ['parent_folder' => $parent_folder];

        if ($this->only_active) {
            $criteria['is_active'] = true;
        }

        $folders = $this->repo->findBy($criteria, ['position' => 'ASC']);

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
        return $this->repo->findBy(
            [$identifier => $values]
        );
    }
}
