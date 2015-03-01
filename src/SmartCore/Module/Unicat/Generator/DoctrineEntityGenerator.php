<?php

namespace SmartCore\Module\Unicat\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;

class DoctrineEntityGenerator extends Generator
{
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
