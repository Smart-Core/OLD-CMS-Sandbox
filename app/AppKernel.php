<?php

require_once __DIR__.'/../src/SmartCore/Bundle/SimpleProfilerBundle/Profiler.php';

use SmartCore\Bundle\CMSBundle\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * Если в папке /src/ находится более одного сайт бандла, то необходимо явно указать какой будет использоваться.
     * Также явное указание, слегка увеличивает производительность.
     */
    //protected $siteName = 'My';

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles()
    {
        $bundles = array(
            //new Abmundi\DatabaseCommandsBundle\AbmundiDatabaseCommandsBundle(), // "abmundi/database-commands-bundle": "dev-master",
            //new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            //new JMS\AopBundle\JMSAopBundle(),
            //new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            //new Happyr\GoogleSiteAuthenticatorBundle\HappyrGoogleSiteAuthenticatorBundle(),
            //new HappyR\SlugifyBundle\HappyRSlugifyBundle(),
            //new Misd\GuzzleBundle\MisdGuzzleBundle(),
            //new Sp\BowerBundle\SpBowerBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Mremi\TemplatingExtraBundle\MremiTemplatingExtraBundle();
            //$bundles[] = new Alb\TwigReflectionBundle\AlbTwigReflectionBundle(); // "alb/twig-reflection-bundle": "*",
        }

        $this->registerSmartCoreCmsBundles($bundles);

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     * @return void
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
