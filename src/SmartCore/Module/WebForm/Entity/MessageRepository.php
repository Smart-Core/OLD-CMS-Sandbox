<?php

namespace SmartCore\Module\WebForm\Entity;

use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
    /**
     * @param WebForm $webForm
     * @param int $status
     * @return \Doctrine\ORM\Query
     */
    public function getFindByStatusQuery(WebForm $webForm, $status)
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->where('m.web_form = :web_form')
            ->andWhere('m.status = :status')
            ->orderBy('m.id', 'ASC')
            ->setParameter('status', $status)
            ->setParameter('web_form', $webForm)
        ;

        return $qb->getQuery();
    }

    /**
     * @param WebForm $webForm
     * @param int $status
     * @return int
     */
    public function getCountByStatus(WebForm $webForm, $status)
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.web_form = :web_form')
            ->andWhere('m.status = :status')
            ->orderBy('m.id', 'ASC')
            ->setParameter('status', $status)
            ->setParameter('web_form', $webForm)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }
}
