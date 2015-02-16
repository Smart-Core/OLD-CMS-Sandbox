<?php

namespace SmartCore\Bundle\CMSBundle\HttpKernel;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

abstract class Kernel extends BaseKernel
{
    /**
     * Подключенные модули CMS.
     *
     * @var array
     */
    protected $modules = [];

    /**
     * @param \Symfony\Component\HttpKernel\Bundle\BundleInterface[] $bundles
     */
    protected function registerSmartCoreCmsBundles(&$bundles)
    {
        $this->registerCmsDependencyBundles($bundles);
        $this->registerCmsModules($bundles);
        $this->autoRegisterSiteBundle($bundles);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Bundle\BundleInterface[] $bundles
     */
    protected function registerCmsModules(&$bundles)
    {
        // Чтение списка модулей. Т.е. модули подключаются почти динамически.
        if (file_exists($this->rootDir . '/usr/modules.ini')) {
            foreach (parse_ini_file($this->rootDir . '/usr/modules.ini') as $module_name => $module_class) {
                if (class_exists($module_class)) {
                    $bundles[] = new $module_class;
                    $this->modules[$module_name] = $module_class;
                } else {
                    // @todo сообщение об отсутсвии класса.
                }
            }
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Bundle\BundleInterface[] $bundles
     */
    protected function registerCmsDependencyBundles(&$bundles)
    {
        $bundles[] = new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle();
        $bundles[] = new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        $bundles[] = new \Symfony\Bundle\SecurityBundle\SecurityBundle();
        $bundles[] = new \Symfony\Bundle\TwigBundle\TwigBundle();
        $bundles[] = new \Symfony\Bundle\MonologBundle\MonologBundle();
        $bundles[] = new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle();
        $bundles[] = new \Symfony\Bundle\AsseticBundle\AsseticBundle();

        $bundles[] = new \Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle();
        $bundles[] = new \Dizda\CloudBackupBundle\DizdaCloudBackupBundle();
        $bundles[] = new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle();
        $bundles[] = new \FM\ElfinderBundle\FMElfinderBundle();
        $bundles[] = new \FOS\RestBundle\FOSRestBundle();
        $bundles[] = new \FOS\UserBundle\FOSUserBundle();
        $bundles[] = new \Genemu\Bundle\FormBundle\GenemuFormBundle();
        $bundles[] = new \JMS\SerializerBundle\JMSSerializerBundle();
        //$bundles[] = new \HWI\Bundle\OAuthBundle\HWIOAuthBundle();
        $bundles[] = new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle();
        $bundles[] = new \Knp\Bundle\DisqusBundle\KnpDisqusBundle();
        $bundles[] = new \Knp\Bundle\MenuBundle\KnpMenuBundle();
        $bundles[] = new \Knp\RadBundle\KnpRadBundle();
        $bundles[] = new \Liip\DoctrineCacheBundle\LiipDoctrineCacheBundle();
        $bundles[] = new \Liip\ThemeBundle\LiipThemeBundle();
        $bundles[] = new \Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle();
        $bundles[] = new \RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle();
        $bundles[] = new \RickySu\TagcacheBundle\TagcacheBundle();
        $bundles[] = new \SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle();
        $bundles[] = new \SmartCore\Bundle\CMSBundle\CMSBundle();
        $bundles[] = new \SmartCore\Bundle\FelibBundle\FelibBundle();
        $bundles[] = new \SmartCore\Bundle\FOSUserBundle\SmartCoreFOSUserBundle();
        $bundles[] = new \SmartCore\Bundle\HtmlBundle\HtmlBundle();
        $bundles[] = new \SmartCore\Bundle\MediaBundle\SmartMediaBundle();
        $bundles[] = new \SmartCore\Bundle\SimpleProfilerBundle\SmartSimpleProfilerBundle();
        $bundles[] = new \SmartCore\Bundle\SitemapBundle\SmartSitemapBundle();
        $bundles[] = new \SmartCore\Bundle\SeoBundle\SmartSeoBundle();
        $bundles[] = new \SmartCore\Bundle\SessionBundle\SmartCoreSessionBundle();
        $bundles[] = new \SmartCore\Bundle\SettingsBundle\SmartCoreSettingsBundle();
        $bundles[] = new \SmartCore\Bundle\UnicatBundle\UnicatBundle();
        $bundles[] = new \Sonata\IntlBundle\SonataIntlBundle();
        $bundles[] = new \Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(); // "stfalcon/tinymce-bundle": "v0.2.1",
        $bundles[] = new \WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle();
        $bundles[] = new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle();

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new \JMS\DebuggingBundle\JMSDebuggingBundle($this); // "jms/debugging-bundle": "dev-master",
            $bundles[] = new \Elao\WebProfilerExtraBundle\WebProfilerExtraBundle();
            $bundles[] = new \SensioLabs\DoctrineQueryStatisticsBundle\SensioLabsDoctrineQueryStatisticsBundle();
            $bundles[] = new \SmartCore\Bundle\CMSGeneratorBundle\CMSGeneratorBundle();
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Bundle\BundleInterface[] $bundles
     * @throws \LogicException
     */
    protected function autoRegisterSiteBundle(&$bundles)
    {
        // Сначала производится попытка подключить указанный вручную сайт.
        if (!empty($this->siteName)) {
            $siteBundleClass = $this->siteName.'SiteBundle\SiteBundle';

            if (class_exists($siteBundleClass)) {
                $bundles[] = new $siteBundleClass();

                return;
            }
        }

        $finder = (new Finder())->directories()->name('*SiteBundle')->name('SiteBundle')->in($this->rootDir.'/../src');

        // Такой подсчет работает быстрее, чем $finder->count();
        $count = 0;
        $dirName = null;

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $count++;
            $dirName = $file->getBasename();
        }

        if ($count == 0 and isset($_SERVER['HTTP_HOST'])) {
            die('Не доступен SiteBundle.</br></br>Сгенерируйте SiteBundle командой <pre>$ app/console cms:generate:sitebundle</pre>');
        }

        if ($count > 1) {
            $response = 'Trying to register two bundles with the same name "SiteBundle"</br></br>Found in /src/:</br>';
            foreach ($finder as $file) {
                $response .= $file->getBasename().'</br>';
            }

            if (isset($_SERVER['HTTP_HOST'])) {
                die($response);
            } else {
                throw new \LogicException(str_replace('</br>', "\n", $response));
            }
        } else {
            $className = '\\' . $dirName.'\\SiteBundle';
            if (class_exists($className)) {
                $bundles[] = new $className();
            }
        }
    }

    protected function getContainerBaseClass()
    {
        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            return '\JMS\DebuggingBundle\DependencyInjection\TraceableContainer';
        }

        return parent::getContainerBaseClass();
    }

    public function boot()
    {
        parent::boot();
        \Profiler::setKernel($this);
    }

    /**
     * Получить список подключенных модулей CMS.
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }
}
