<?php
namespace SmartCore;

// @todo убрать отсюда.
require_once __DIR__.'/../src/SmartCore/Profiler.php';

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),

            //new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(), // "sensio/framework-extra-bundle": "2.2.*",
            //new \JMS\JMSAopBundle\JMSAopBundle(),
            //new \JMS\DiExtraBundle\JMSDiExtraBundle($this), // "jms/di-extra-bundle": "1.3.*",
            //new \JMS\SecurityExtraBundle\JMSSecurityExtraBundle(), // "jms/security-extra-bundle": "1.4.*",

            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \FOS\UserBundle\FOSUserBundle(),
            //new \FOS\FacebookBundle\FOSFacebookBundle(),
            new \Liip\ThemeBundle\LiipThemeBundle(),
            new \FM\ElfinderBundle\FMElfinderBundle(),
            new \RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),
            new \Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new \Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            //new \Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            
            new \SmartCore\Bundle\SessionBundle\SmartCoreSessionBundle(),
            new \SmartCore\Bundle\CMSBundle\CMSBundle(),
            new \SmartCore\Bundle\HtmlBundle\HtmlBundle(),
            new \SmartCore\Bundle\FOSUserBundle\SmartCoreFOSUserBundle(),
            //new \SmartCore\Bundle\UserBundle\SmartCoreUserBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            //$bundles[] = new \JMS\DebuggingBundle\JMSDebuggingBundle($this); // "jms/debugging-bundle": "dev-master",
            $bundles[] = new \Elao\WebProfilerExtraBundle\WebProfilerExtraBundle();
//            $bundles[] = new \Leek\GitDebugBundle\LeekGitDebugBundle();
            $bundles[] = new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle();
            $bundles[] = new \Egulias\ListenersDebugCommandBundle\EguliasListenersDebugCommandBundle();
            $bundles[] = new \SensioLabs\DoctrineQueryStatisticsBundle\SensioLabsDoctrineQueryStatisticsBundle();
//            $bundles[] = new \Alb\TwigReflectionBundle\AlbTwigReflectionBundle(); // "alb/twig-reflection-bundle": "*",
//            $bundles[] = new \Sf2gen\Bundle\ConsoleBundle\Sf2genConsoleBundle();
        }

        // Чтение списка модулей. Т.е. модули подключаются почти динамически ;)
        if (file_exists($this->rootDir . '/usr/modules.ini')) {
            foreach (parse_ini_file($this->rootDir . '/usr/modules.ini') as $module_class) {
                if (class_exists($module_class)) {
                    $bundles[] = new $module_class;
                }
            }
        }

        return $bundles;
    }

    public function boot()
    {
        parent::boot();
        \Profiler::setKernel($this);
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /*
    protected function getContainerBaseClass()
    {
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            return '\JMS\DebuggingBundle\DependencyInjection\TraceableContainer';
        }

        return parent::getContainerBaseClass();
    }
    */
    
    /**
     * Переопределённая директория для файлов кеша.
     * 
     * @return string
     */
    public function getCacheDir()
    {
        return $this->rootDir.'/../var/cache/'.$this->environment;
    }

    /**
     * Переопределённая директория для лог файлов.
     * 
     * @return string
     */
    public function getLogDir()
    {
        return $this->rootDir.'/../var/logs';
    }
}
