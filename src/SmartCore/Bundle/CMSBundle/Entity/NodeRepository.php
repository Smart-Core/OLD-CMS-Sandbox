<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;

class NodeRepository extends EntityRepository
{
    /**
     * @param array $list
     * @return Node[]
     */
    public function findIn(array $list)
    {
        $list_string = '';
        foreach ($list as $node_id) {
            $list_string .= $node_id . ',';
        }

        $list_string = substr($list_string, 0, strlen($list_string) - 1); // обрезка послезней запятой.

        if (false == $list_string) {
            return [];
        }

        return $this->_em->createQuery("
            SELECT n
            FROM CMSBundle:Node n
            WHERE n.id IN({$list_string})
            ORDER BY n.position ASC
        ")->getResult();
    }

    /**
     * @param Block|int $block
     * @return int
     */
    public function countInBlock($block)
    {
        if ($block instanceof Block) {
            $block = $block->getId();
        }

        $query = $this->_em->createQuery("
            SELECT COUNT(n.id)
            FROM CMSBundle:Node n
            JOIN CMSBundle:Block b
            WHERE b.id = {$block}
            AND n.block = b
        ");

        return $query->getSingleScalarResult();
    }
}
