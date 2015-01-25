<?php

namespace SmartCore\Bundle\CMSBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use SmartCore\Bundle\CMSBundle\Entity\Folder;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FolderDoctrineSubscriber implements EventSubscriber
{
    use ContainerAwareTrait;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Folder) {
            $this->checkRelations($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->prePersist($args);
    }

    protected function checkRelations(Folder $folder)
    {
        $uriPart = $folder->getUriPart();

        if (empty($uriPart) and $folder->getId() != 1) {
            $folder->setUriPart($folder->getId());
        }

        // Защита от цикличных зависимостей.
        $parent = $folder->getParentFolder();

        if (null == $parent) {
            return;
        }

        $cnt = 30;
        $ok = false;
        while ($cnt--) {
            if ($parent->getId() == 1) {
                $ok = true;
                break;
            } else {
                $parent = $parent->getParentFolder();
                continue;
            }
        }

        // Если обнаружена циклическая зависимость, тогда родитель выставляется корневая папка, которая имеет id = 1.
        if (!$ok) {
            $folder->setParentFolder($this->container->get('cms.folder')->get(1));
        }
    }
}
