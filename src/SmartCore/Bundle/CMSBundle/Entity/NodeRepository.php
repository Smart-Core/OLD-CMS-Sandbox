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

        $list_string = substr($list_string, 0, strlen($list_string)-1);

        if (false == $list_string) {
            return [];
        }

        return $this->_em->createQuery("
            SELECT n
            FROM {$this->_entityName} n
            WHERE n.node_id IN({$list_string})
            ORDER BY n.position ASC
        ")->getResult();
    }
}
