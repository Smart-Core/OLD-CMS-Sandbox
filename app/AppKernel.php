<?php

if (!defined('START_TIME')) {
    define('START_TIME', microtime(true));
}
if (!defined('START_MEMORY')) {
    define('START_MEMORY', memory_get_usage());
}

use SmartCore\Bundle\CMSBundle\CMSAppKernel;

class AppKernel extends CMSAppKernel
{
    /**
     * Если в папке /src/ находится более одного сайт бандла, то необходимо явно указать какой будет использоваться.
     * Также явное указание, слегка увеличивает производительность.
     */
    //protected $siteName = 'My';

    /**
     * Если требуются, можно зарегистирировать дополнительные бандлы,
     * но перед return, необходимо вызвать $this->registerSmartCoreCmsBundles($bundles);
     *
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles()
    {
        $bundles = array(
            new Dizda\CloudBackupBundle\DizdaCloudBackupBundle(),
            new SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle(),
            new SmartCore\Bundle\SitemapBundle\SmartSitemapBundle(),

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
            //$bundles[] = new JMS\DebuggingBundle\JMSDebuggingBundle($this); // "jms/debugging-bundle": "dev-master",
            //$bundles[] = new Alb\TwigReflectionBundle\AlbTwigReflectionBundle(); // "alb/twig-reflection-bundle": "*",
        }

        $this->registerSmartCoreCmsBundles($bundles);

        return $bundles;
    }

    /*
    protected function getContainerBaseClass()
    {
        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            return '\JMS\DebuggingBundle\DependencyInjection\TraceableContainer';
        }

        return parent::getContainerBaseClass();
    }
    */
}
