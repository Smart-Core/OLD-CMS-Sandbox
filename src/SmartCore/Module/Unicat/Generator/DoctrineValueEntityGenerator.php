<?php

namespace SmartCore\Module\Unicat\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;

class DoctrineValueEntityGenerator extends Generator
{
    /**
     * @param string $dir
     * @param string $type
     * @param string $className
     * @param string $namespace
     * @param string $table_name
     * @param string $table_prefix
     * @throws \Exception
     */
    public function generate($dir, $configuration_name, $type, $className, $namespace, $table_name, $table_prefix = 'unicat_')
    {
        switch ($type) {
            case 'checkbox':
                $type = 'Bool';
                break;
            case 'integer':
                $type = 'Int';
                break;
            case 'smallint':
                $type = 'Smallint';
                break;
            case 'String':
                $type = 'string';
                break;
            case 'text':
                $type = 'Text';
                break;
            default:
                throw new \Exception('Unsupported value type: '.$type);
        }

        $parameters = [
            'configuration_name' => $configuration_name,
            'class_name'    => $className,
            'namespace'     => $namespace,
            'type'          => $type,
            'table_name'    => $table_name,
            'table_prefix'  => $table_prefix,
        ];

        // @todo для multiselect использовать ValueType.php.twig
        $this->renderFile('entity/ValueUniqueType.php.twig', $dir.'/'.$className.'.php', $parameters);
    }
}
