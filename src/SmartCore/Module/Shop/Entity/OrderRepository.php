<?php

namespace SmartCore\Module\Shop\Entity;

use Doctrine\ORM\EntityRepository;
use Smart\CoreBundle\Doctrine\RepositoryTrait;
use SmartCore\Bundle\CMSBundle\Model\UserModel;

class OrderRepository extends EntityRepository
{
    use RepositoryTrait\FindByQuery;

    /**
     * Получить все, кроме "в корзине" и "удалённых".
     *
     * @param null|UserModel|int $user
     *
     * @return array
     */
    public function findAllVisible($user = null)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->where('e.status != :PENDING')
            ->andWhere('e.status != :DELETED')
            ->setParameter('PENDING', Order::STATUS_PENDING)
            ->setParameter('DELETED', Order::STATUS_DELETED)
        ;

        if ($user) {
            $qb->andWhere('e.user = :user')
               ->setParameter('user', $user)
            ;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param null|UserModel|int $user
     *
     * @return array
     */
    public function findActive($user = null)
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->where($qb->expr()->orX(
                $qb->expr()->eq('e.status', ':PROCESSING'),
                $qb->expr()->eq('e.status', ':SHIPPING')
            ))
            ->setParameter('PROCESSING', Order::STATUS_PROCESSING)
            ->setParameter('SHIPPING',   Order::STATUS_SHIPPING)
        ;

        if ($user) {
            $qb->andWhere('e.user = :user')
               ->setParameter('user', $user)
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
