<?php

namespace SmartCore\Bundle\CMSGeneratorBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

class SiteBundleGenerator extends Generator
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

        $this->renderFile('sitebundle/Bundle.php.twig', $dir.'/'.$bundle.'.php', $parameters);
        $this->renderFile('sitebundle/Controller.php.twig', $dir.'/Controller/DefaultController.php', $parameters);
        $this->renderFile('sitebundle/screen.css.twig', $dir.'/Resources/public/css/screen.css', $parameters);
        $this->renderFile('sitebundle/index.html.twig.twig', $dir.'/Resources/views/index.html.twig', $parameters);
        $this->renderFile('sitebundle/welcome.html.twig.twig', $dir.'/Resources/views/Default/welcome.html.twig', $parameters);

        $this->renderFile('sitebundle/config.yml.twig', $dir.'/Resources/config/config.yml', $parameters);
        $this->renderFile('sitebundle/routing.'.$format.'.twig', $dir.'/Resources/config/routing.'.$format, $parameters);
        $this->renderFile('module/services.'.$format.'.twig', $dir.'/Resources/config/services.'.$format, $parameters);
        $this->renderFile('module/settings.yml.twig', $dir.'/Resources/config/settings.yml', $parameters);

        $this->renderFile('sitebundle/messages.ru.yml', $dir.'/Resources/translations/messages.ru.yml', $parameters);

        $this->filesystem->mkdir($dir.'/Entity');
        $this->filesystem->mkdir($dir.'/Resources/public/css');
        $this->filesystem->mkdir($dir.'/Resources/public/images');
        $this->filesystem->mkdir($dir.'/Resources/public/js');
    }
}
