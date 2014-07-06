<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @todo кеширование
 */
class EngineConfig
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $settingsRepo;

    /**
     * @param ContainerInterface $container
     *
     * @todo сделать позднюю загрузку репозитория т.е. только в том случае, если нужно прочитать из БД.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->settingsRepo = $container->get('doctrine.orm.entity_manager')->getRepository('CMSBundle:Setting');
    }

    /**
     * @param string $bundle
     * @param string $key
     * @return mixed
     */
    public function get($bundle, $key)
    {
        $setting =$this->settingsRepo->findOneBy([
            'bundle' => $bundle,
            'key' => $key,
        ]);

        if (empty($setting)) {
            throw new \Exception('Wrong bundle-key pair in setting.');
        }

        return $setting->getValue();
    }
}
