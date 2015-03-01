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
            $list_string .= $node_id.',';
        }

        $list_string = substr($list_string, 0, strlen($list_string) - 1); // обрезка послезней запятой.

        if (false == $list_string) {
            return [];
        }

        return $this->_em->createQuery("
            SELECT n
            FROM CMSBundle:Node AS n
            WHERE n.id IN({$list_string})
            ORDER BY n.position ASC
        ")->getResult();
    }

    /**
     * @param Region|int $region
     * @return int
     */
    public function countInRegion($region)
    {
        $query = $this->_em->createQuery("
            SELECT COUNT(n.id)
            FROM CMSBundle:Node AS n
            JOIN CMSBundle:Region AS r
            WHERE r.id = :region
            AND n.region = r
        ")->setParameter('region', $region);

        return $query->getSingleScalarResult();
    }

    /**
     * @param int|Folder $folder
     * @param array $exclude_nodes
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function getInFolder($folder, array $exclude_nodes = [])
    {
        if ($folder instanceof Folder) {
            $folder = $folder->getId();
        }

        $engine_nodes_table = $this->_class->getTableName();

        // @todo переделать на SQL для поддержки PgSQL
        $sql = "
            SELECT id
            FROM $engine_nodes_table
            WHERE folder_id = '$folder'
            AND is_active = '1'
        ";

        // Исключение ранее включенных нод.
        foreach ($exclude_nodes as $node_id) {
            $sql .= " AND id != '{$node_id}'";
        }

        $sql .= ' ORDER BY position';

        return $this->_em->getConnection()->query($sql);
    }

    /**
     * @param int|Folder $folder
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function getInheritedInFolder($folder)
    {
        if ($folder instanceof Folder) {
            $folder = $folder->getId();
        }

        $engine_nodes_table           = $this->_class->getTableName();
        $engine_regions_inherit_table = $this->_em->getClassMetadata('CMSBundle:Region')->getAssociationMapping('folders')['joinTable']['name'];

        $sql = "
            SELECT n.id
            FROM $engine_nodes_table AS n,
                $engine_regions_inherit_table AS ri
            WHERE n.region_id = ri.region_id
                AND n.is_active = 1
                AND n.folder_id = '$folder'
                AND ri.folder_id = '$folder'
            ORDER BY n.position ASC
        ";

        return $this->_em->getConnection()->query($sql);
    }
}
