<?php

namespace SmartCore\Bundle\FelibBundle\Service;

use Doctrine\ORM\EntityManager;
use RickySu\Tagcache\Adapter\TagcacheAdapter;
use SmartCore\Bundle\FelibBundle\Entity\Library;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FelibService
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * Список всех доступных скриптов.
     *
     * @var Library[]
     */
    protected $scripts;

    /**
     * Список запрошенных библиотек.
     */
    protected $calledLibs = [];

    /**
     * Путь до ресурсов.
     *
     * @var string
     */
    protected $globalAssets;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var TagcacheAdapter
     */
    protected $tagcache;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param ContainerInterface $continer
     * @param TagcacheAdapter $tagcache
     */
    public function __construct(EntityManager $em, ContainerInterface $continer, TagcacheAdapter $tagcache)
    {
        $this->basePath     = $continer->get('request')->getBasePath() . '/';
        $this->globalAssets = $this->basePath . 'bundles/felib/';
        $this->db           = $em->getConnection();
        $this->em           = $em;
        $this->tagcache     = $tagcache;

        $cache_key = md5('smart_felib_all_scripts');
        if (false == $this->scripts = $tagcache->get($cache_key)) {
            $this->scripts = [];

            foreach ($em->getRepository('FelibBundle:Library')->findAll([], ['proirity' => 'DESC']) as $lib) {
                $this->scripts[$lib->getName()] = $lib;
            }

            $tagcache->set($cache_key, $this->scripts, ['smart_felib']);
        }
    }

    /**
     * Запрос библиотеки.
     *
     * @param string $name
     * @param string $version
     */
    public function call($name, $version = false)
    {
        $this->calledLibs[$name] = $version;
    }

    /**
     * Получить список запрошенных либ.
     *
     * @return array
     */
    public function all()
    {
        $cache_key = md5('smart_felib_called_libs' . serialize($this->calledLibs) . $this->basePath);
        if (false == $output = $this->tagcache->get($cache_key)) {
            $output = [];
        } else {
            return $output;
        }

        // Т.к. запрашивается в произвольном порядке - сначала надо сформировать массив с ключами в правильном порядке.
        foreach ($this->scripts as $key => $value) {
            $output[$key] = false;
        }

        // Затем вычисляются зависимости.
        $flag = 1;
        while ($flag == 1) {
            $flag = 0;
            foreach ($this->calledLibs as $name => $value) {
                // @todo пока можно обработать зависимость только от одной либы, далее надо сделать списки, например "prototype, scriptaculous".
                $related_by = isset($this->scripts[$name]) ? $this->scripts[$name]->getRelatedBy() : null;

                if (!empty($related_by) and !isset($this->calledLibs[$related_by])) {
                    $this->calledLibs[$related_by] = false;
                    $flag = 1;
                }
            }
        }

        // @todo сделать возможность конфигурирования из файлов.
        foreach ($this->calledLibs as $name => $version) {
            if (!isset($this->scripts[$name])) {
                continue;
            }

            $pathEntity = $this->em->getRepository('FelibBundle:LibraryPath')->findOneBy([
                'lib_id'  => $this->scripts[$name]->getId(),
                'version' => empty($version) ? $this->scripts[$name]->getCurrentVersion() : $version,
            ])->getPath();

            $path = strpos($pathEntity, 'http://') === 0 ? $pathEntity : $this->globalAssets . $pathEntity;

            foreach (explode(',', $this->scripts[$name]->getFiles()) as $file) {
                if (substr($file, strrpos($file, '.') + 1) === 'css') {
                    $output[$name]['css'][] = $path . $file;
                }

                if (substr($file, strrpos($file, '.') + 1) === 'js') {
                    $output[$name]['js'][] = $path . $file;
                }
            }
        }

        // Удаляются пустые ключи
        foreach ($output as $key => $value) {
            if ($output[$key] === false) {
                unset($output[$key]);
            }
        }

        $this->tagcache->set($cache_key, $output, ['smart_felib']);

        return $output;
    }
}
