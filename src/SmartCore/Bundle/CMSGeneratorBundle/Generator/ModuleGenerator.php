<?php

namespace SmartCore\Bundle\CMSGeneratorBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

class ModuleGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate($namespace, $bundle, $dir, $format)
    {
        $dir .= '/'.strtr($namespace, '\\', '/');
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the module as the target directory "%s" exists but is a file.', realpath($dir)));
            }
            $files = scandir($dir);
            if ($files != array('.', '..')) {
                throw new \RuntimeException(sprintf('Unable to generate the module as the target directory "%s" is not empty.', realpath($dir)));
            }
            if (!is_writable($dir)) {
                throw new \RuntimeException(sprintf('Unable to generate the module as the target directory "%s" is not writable.', realpath($dir)));
            }
        }

        $basename = substr($bundle, 0, -6);
        $parameters = array(
            'namespace' => $namespace,
            'bundle'    => $bundle,
            'format'    => $format,
            'bundle_basename' => $basename,
            'bundle_basename_camelize' => Container::camelize($basename),
            'extension_alias' => Container::underscore($basename),
        );

        $this->renderFile('module/Bundle.php.twig', $dir.'/'.$bundle.'.php', $parameters);
        $this->renderFile('module/Controller.php.twig', $dir.'/Controller/'.Container::camelize($basename).'Controller.php', $parameters);
        $this->renderFile('module/ControllerAdmin.php.twig', $dir.'/Controller/Admin'.Container::camelize($basename).'Controller.php', $parameters);
        $this->renderFile('module/NodePropertiesFormType.php.twig', $dir.'/Form/Type/NodePropertiesFormType.php', $parameters);
        $this->renderFile('module/index.html.twig.twig', $dir.'/Resources/views/index.html.twig', $parameters);
        $this->renderFile('module/index_admin.html.twig.twig', $dir.'/Resources/views/Admin/index.html.twig', $parameters);

        $this->renderFile('module/routing.'.$format.'.twig', $dir.'/Resources/config/routing.'.$format, $parameters);
        $this->renderFile('module/routing_admin.'.$format.'.twig', $dir.'/Resources/config/routing_admin.'.$format, $parameters);
        $this->renderFile('module/services.'.$format.'.twig', $dir.'/Resources/config/services.'.$format, $parameters);
        $this->renderFile('module/settings.yml.twig', $dir.'/Resources/config/settings.yml', $parameters);

        $this->renderFile('module/messages.ru.yml', $dir.'/Resources/translations/messages.ru.yml', $parameters);

        $this->filesystem->mkdir($dir.'/Resources/public/css');
        $this->filesystem->mkdir($dir.'/Resources/public/images');
        $this->filesystem->mkdir($dir.'/Resources/public/js');
    }
}
