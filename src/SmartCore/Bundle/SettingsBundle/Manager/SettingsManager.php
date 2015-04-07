<?php

namespace SmartCore\Bundle\SettingsBundle\Manager;

use RickySu\Tagcache\Adapter\TagcacheAdapter;
use SmartCore\Bundle\SettingsBundle\Entity\Setting;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingsManager
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
    public function __construct(ContainerInterface $container, TagcacheAdapter $tagcache)
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
            $this->settingsRepo = $this->container->get('doctrine.orm.entity_manager')->getRepository('SmartCoreSettingsBundle:Setting');
        }
    }

    /**
     * @param string|null $bundle
     * @return array
     */
    public function all($bundle = null)
    {
        $this->initRepo();

        if ($bundle) {
            return $this->settingsRepo->findBy(['bundle' => $bundle], ['name' => 'ASC']);
        }

        return $this->settingsRepo->findBy([], ['bundle' => 'ASC', 'name' => 'ASC']);
    }

    /**
     * @param int $id
     * @return Setting|null
     */
    public function findById($id)
    {
        $this->initRepo();

        return $this->settingsRepo->find($id);
    }

    /**
     * @param string $bundle
     * @param string $name
     * @return mixed
     */
    public function get($bundle, $name)
    {
        $cache_key = md5('smart_setting_'.$bundle.$name);

        if (false == $setting = $this->tagcache->get($cache_key)) {
            $this->initRepo();

            $setting = $this->settingsRepo->findOneBy([
                'bundle' => $bundle,
                'name' => $name,
            ]);

            if (empty($setting)) {
                throw new \Exception('Wrong bundle-name pair in setting. (Bundle: '.$bundle.', Name: '.$name.')');
            }

            $this->tagcache->set($cache_key, $setting, ['smart.settings']);
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

        $this->tagcache->deleteTag('smart.settings');

        return true;
    }
}
