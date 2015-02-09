<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use RickySu\Tagcache\Adapter\TagcacheAdapter;
use SmartCore\Bundle\CMSBundle\Entity\Setting;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EngineConfig
{
    use ContainerAwareTrait;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $settingsRepo = null;

    /**
     * @var \RickySu\Tagcache\Adapter\TagcacheAdapter
     */
    protected $tagcache;

    /**
     * @param ContainerInterface $container
     * @param TagcacheAdapter $tagcache
     */
    public function __construct(ContainerInterface $container, TagcacheAdapter $tagcache = null)
    {
        $this->container = $container;
        $this->tagcache  = $tagcache;
    }

    /**
     * Lazy repository initialization.
     */
    protected function initRepo()
    {
        if (null === $this->settingsRepo) {
            $this->settingsRepo = $this->container->get('doctrine.orm.entity_manager')->getRepository('CMSBundle:Setting');
        }
    }

    /**
     * @param string $bundle
     * @param string $key
     * @return mixed
     */
    public function get($bundle, $key)
    {
        $cache_key = md5('cms_setting_'.$bundle.$key);

        if (false == $setting = $this->tagcache->get($cache_key)) {
            $this->initRepo();

            $setting = $this->settingsRepo->findOneBy([
                'bundle' => $bundle,
                'key' => $key,
            ]);

            if (empty($setting)) {
                throw new \Exception('Wrong bundle-key pair in setting.');
            }

            $this->tagcache->set($cache_key, $setting, ['cms.settings']);
        }

        return $setting->getValue();
    }

    /**
     * @param Setting $setting
     * @return bool
     */
    public function updateEntity(Setting $setting)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $em->persist($setting);
        $em->flush($setting);

        $this->tagcache->deleteTag('cms.settings');

        return true;
    }
}
