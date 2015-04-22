<?php

namespace SmartCore\Module\Gallery\Entity;

use Doctrine\ORM\EntityRepository;

class PhotoRepository extends EntityRepository
{
    /**
     * @param Album|int $album
     *
     * @return int
     */
    public function countInAlbum($album)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->where('p.album = :album')
            ->setParameter('album', $album)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }
}
