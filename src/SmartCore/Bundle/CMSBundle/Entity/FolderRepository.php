<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FolderRepository extends EntityRepository
{
    /**
     * @param Folder $parent_folder
     * @return Folder[]
     */
    public function findByParent(Folder $parent_folder = null)
    {
        return $this->findBy(['parent_folder' => $parent_folder]);
    }
}
