<?php

namespace SmartCore\Bundle\EngineBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FolderRepository extends EntityRepository
{
    public function findByParent(Folder $parent_folder = null)
    {
        return $this->findBy(['parent_folder' => $parent_folder]);
    }
}
