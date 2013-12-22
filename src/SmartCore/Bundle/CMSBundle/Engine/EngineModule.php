<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAware;

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
     */
    public function install()
    {
        die('@todo');
    }
}
