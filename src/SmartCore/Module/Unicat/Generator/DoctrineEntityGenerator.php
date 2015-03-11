<?php

namespace SmartCore\Module\Unicat\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;

class DoctrineEntityGenerator extends Generator
{
    /**
     * @param string $dir
     * @param string $configuration_name
     * @param string $namespace
     * @param string $table_prefix
     */
    public function generate($dir, $configuration_name, $namespace, $table_prefix = 'unicat_')
    {
        $parameters = [
            'configuration_name' => $configuration_name,
            'namespace'          => $namespace,
            'table_prefix'       => $table_prefix,
        ];

        $entities = [
            'Attribute',
            'AttributesGroup',
            'Category',
            'Item',
        ];

        foreach ($entities as $entity) {
            $this->renderFile('entity/'.$entity.'.php.twig', $dir.'/'.$entity.'.php', $parameters);
        }
    }
}
