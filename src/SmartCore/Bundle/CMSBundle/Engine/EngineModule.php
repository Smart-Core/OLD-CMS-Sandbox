<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class EngineModule extends ContainerAware
{
    protected $configFile;
    protected $modules = [];
    protected $initialized = false;

    /**
     * Initializes the collection of modules.
     */
    public function initialize()
    {
        if (!$this->initialized) {
            $this->configFile = $this->container->get('kernel')->getRootDir() . '/usr/modules.ini';

            foreach (parse_ini_file($this->configFile) as $module_name => $_dummy) {
                $this->modules[$module_name] = $this->container->get('kernel')->getBundle($module_name . 'Module');
            }

            $this->initialized = true;
        }
    }
        
    /**
     * Получить список всех модулей.
     * 
     * @return array
     */
    public function all()
    {
        return $this->modules;
    }
    
    /**
     * Получить информацию о модуле.
     *
     * @param string $name
     * @return string|null
     */
    public function get($name)
    {
        if (isset($this->modules[$name])) {
            return $this->modules[$name];
        } else {
            return null;
        }
    }

    /**
     * Установка модуля.
     *
     * @param string $filename
     *
     * @todo доделать.
     */
    public function install($filename)
    {
        $rootDir = $this->container->get('kernel')->getRootDir();
        $distDir = $rootDir . '/../dist';

        // 1) Распаковка архива.
        $zip = new \ZipArchive();
        $zip->open($distDir . '/' . $filename);
        $zip->extractTo($rootDir . '/../src');

        // 2) Подключение модуля.
        $modulesList = $this->container->get('kernel')->getModules();
        $modulesList['Example'] = '\SmartCore\Module\Example\ExampleModule';
        ksort($modulesList);

        $modulesIni = '';
        foreach ($modulesList as $key => $value) {
            $modulesIni .= "$key = $value\n";
        }

        file_put_contents($rootDir.'/usr/modules.ini', $modulesIni);

        // 3) Очистка кэша.
        $finderCache = new Finder();
        $finderCache->ignoreDotFiles(false)
            ->ignoreVCS(true)
            ->depth('== 0')
            ->in($this->container->get('kernel')->getCacheDir() . '/../');

        $fs = new Filesystem();
        /** @var \Symfony\Component\Finder\SplFileInfo $file*/
        foreach ($finderCache as $file) {
            try {
                $fs->remove($file->getPath());
            } catch (IOException $e) {
                // do nothing
            }
        }

        // 4) Установка ресурсов (Resources/public).
        $application = new Application($this->container->get('kernel'));
        $application->setAutoExit(false);
        $input = new ArrayInput(['command' => 'assets:install', 'target' => $rootDir . '/../web']);
        $output = new BufferedOutput();
        $retval = $application->run($input, $output);
    }
}
