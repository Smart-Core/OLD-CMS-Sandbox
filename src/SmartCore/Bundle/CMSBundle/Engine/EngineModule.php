<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;

class EngineModule extends ContainerAware
{
    /**
     * @var \SmartCore\AppKernel
     */
    protected $kernel;
    protected $modules = [];
    protected $initialized = false;

    /**
     * Constructor.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->initialize();
    }    
    
    /**
     * Initializes the collection of modules.
     */
    public function initialize()
    {
        if (!$this->initialized) {
            $configFile = $this->kernel->getRootDir() . '/usr/modules.ini';

            foreach (parse_ini_file($configFile) as $module_name => $_dummy) {
                $this->modules[$module_name] = $this->kernel->getBundle($module_name . 'Module');
            }

            $this->initialized = true;
        }
    }
        
    /**
     * Получить список всех модулей.
     * 
     * @return \SmartCore\Bundle\CMSBundle\Module\Bundle[]
     */
    public function all()
    {
        return $this->modules;
    }
    
    /**
     * Получить информацию о модуле.
     *
     * @param string $name
     * @return \SmartCore\Bundle\CMSBundle\Module\Bundle|null
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
        $rootDir = $this->kernel->getRootDir();
        $distDir = $rootDir . '/../dist';

        // 1) Распаковка архива.
        $zip = new \ZipArchive();
        $zip->open($distDir . '/' . $filename);
        $zip->extractTo($rootDir . '/../src');

        // 2) Подключение модуля.
        $modulesList = $this->kernel->getModules();
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
            ->in($this->kernel->getCacheDir() . '/../');

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
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(['command' => 'assets:install', 'target' => $rootDir . '/../web']);
        $output = new BufferedOutput();
        $retval = $application->run($input, $output);
    }
}
