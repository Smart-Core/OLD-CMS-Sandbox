<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Form\Type\NodeDefaultPropertiesFormType;
use SmartCore\Bundle\CMSBundle\Form\Type\NodeFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class EngineNode
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EngineContext
     */
    protected $engine_context;

    /**
     * @var FormFactoryInterface
     */
    protected $form_factory;

    /**
     * @var \SmartCore\Bundle\CMSBundle\Entity\NodeRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $db_prefix;

    /**
     * @var EngineContext
     */
    protected $context;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Список всех нод, запрошенных в текущем контексте.
     *
     * @var array
     */
    protected $nodes = [];

    /**
     * Является ли нода только что созданной?
     *
     * Применяется для вызова метода createNode() модуля после создания ноды.
     *
     * @var bool
     *
     * @todo пересмотреть логику, может быть в сущности запоминать этот флаг?
     */
    protected $is_just_created = false;

    /**
     * @var \RickySu\Tagcache\Adapter\TagcacheAdapter
     */
    protected $tagcache;

    /**
     * @param EntityManager $em
     * @param FormFactoryInterface $form_factory
     * @param KernelInterface $kernel
     * @param EngineContext $engineContext
     * @param string $database_table_prefix
     */
    public function __construct(
        EntityManager $em,
        FormFactoryInterface $form_factory,
        KernelInterface $kernel,
        EngineContext $engineContext,
        $database_table_prefix = '',
        $tagcache
    ) {
        $this->context      = $engineContext;
        $this->em           = $em;
        $this->form_factory = $form_factory;
        $this->kernel       = $kernel;
        $this->repository   = $em->getRepository('CMSBundle:Node');
        $this->db           = $em->getConnection();
        $this->db_prefix    = $database_table_prefix;
        $this->tagcache     = $tagcache;
    }

    /**
     * @return Node
     */
    public function create()
    {
        $this->is_just_created = true;

        return new Node();
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param  mixed $data     The initial data for the form
     * @param  array $options  Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function createForm($data = null, array $options = [])
    {
        return $this->form_factory->create(new NodeFormType(), $data, $options);
    }

    /**
     * @param  int $id
     * @return Node|null
     */
    public function get($id)
    {
        if (null === $id) {
            return null;
        }

        if (isset($this->nodes[$id])) {
            return $this->nodes[$id];
        }

        return $this->repository->find($id);
    }

    /**
     * @param Node $node
     */
    public function update(Node $node)
    {
        /** @var \SmartCore\Bundle\CMSBundle\Module\Bundle $module */
        $module = $this->kernel->getBundle($node->getModule() . 'Module');

        // Свежесозданная нода выполняет свои действия, а также устанавливает параметры по умолчанию.
        if ($this->is_just_created) {
            $module->createNode($node);

            $this->is_just_created = false;
        } else {
            $module->updateNode($node);
        }

        $this->em->persist($node);
        $this->em->flush($node);
    }

    /**
     * Получить форму редактирования параметров подключения модуля.
     *
     * @param  string $module_name
     * @return FormTypeInterface
     */
    public function getPropertiesFormType($module_name)
    {
        $reflector = new \ReflectionClass(get_class($this->kernel->getBundle($module_name . 'Module')));
        $form_class_name = '\\' . $reflector->getNamespaceName() . '\Form\Type\NodePropertiesFormType';

        if (class_exists($form_class_name)) {
            return new $form_class_name;
        } else {
            // @todo может быть гибче настраивать форму параметров по умолчанию?.
            return new NodeDefaultPropertiesFormType();
        }
    }

    /**
     * Создание списка всех запрошеных нод, в каких блоках они находятся и с какими
     * параметрами запускаются модули.
     *
     * @param  array  $router_data
     * @return Node[] $nodes
     */
    public function buildList(array $router_data)
    {
        if (!empty($this->nodes)) {
            return $this->nodes;
        }

        $this->nodes = [];

        if ($router_data['http_method'] == 'GET') {
            $cache_key = md5('cms_node_list' . serialize($router_data));
            if (false == $this->nodes = $this->tagcache->get($cache_key)) {
                $this->nodes = [];
            } else {
                return $this->nodes;
            }
        }

        \Profiler::start('buildNodesList');

        $used_nodes = [];
        $lockout_nodes = [ // @todo блокировку нод.
            'single'  => [], // Блокировка нод в папке, без наследования.
            'inherit' => [], // Блокировка нод в папке, с наследованием.
            'except'  => [], // Блокировка всех нод в папке, кроме заданных.
        ];

        /** @var $folder \SmartCore\Bundle\CMSBundle\Entity\Folder */
        foreach ($router_data['folders'] as $folder) {
            // single каждый раз сбрасывается и устанавливается заново для каждоый папки.
            // @todo блокировку нод.
            /*
            $lockout_nodes['single'] = [];
            if (isset($parsed_uri_value['lockout_nodes']['single']) and !empty($parsed_uri_value['lockout_nodes']['single'])) {
                //$lockout_nodes['single'] = $parsed_uri_value['lockout_nodes']['single'];
                $tmp = explode(',', $parsed_uri_value['lockout_nodes']['single']);
                foreach ($tmp as $single_value) {
                    $t = trim($single_value);
                    if (!empty($t)) {
                        $lockout_nodes['single'][trim($single_value)] = 'blocked'; // ставлю тупо 'blocked', но главное в массиве с блокировками, это индексы.
                    }
                }
            }

            // Блокировка нод в папке, с наследованием.
            if (isset($parsed_uri_value['lockout_nodes']['inherit']) and !empty($parsed_uri_value['lockout_nodes']['inherit'])) {
                $tmp = explode(',', $parsed_uri_value['lockout_nodes']['inherit']);
                foreach ($tmp as $inherit_value) {
                    $t = trim($inherit_value);
                    if (!empty($t)) {
                        $lockout_nodes['inherit'][trim($inherit_value)] = 'blocked'; // ставлю тупо 'blocked', но главное в массиве с блокировками, это индексы.
                    }
                }
            }

            // Блокировка всех нод в папке, кроме заданных.
            if (isset($parsed_uri_value['lockout_nodes']['except']) and !empty($parsed_uri_value['lockout_nodes']['except'])) {
                $tmp = explode(',', $parsed_uri_value['lockout_nodes']['except']);
                foreach ($tmp as $except_value) {
                    $t = trim($except_value);
                    if (!empty($t)) {
                        $lockout_nodes['except'][trim($except_value)] = 'blocked'; // ставлю тупо 'blocked', но главное в массиве с блокировками, это индексы.
                    }
                }
            }
            */

            // @todo сейчас список нод запрашивается плоским SQL, надо как-то на ORM перевести,
            // а еще лучше убрать это в NodeRepository т.о. избавляемся от зависимостей $db и $db_prefix.
            $sql = false;

            // Обработка последней папки т.е. текущей.
            if ($folder->getId() == $this->context->getCurrentFolderId()) {
                $sql = "SELECT *
                    FROM {$this->db_prefix}engine_nodes
                    WHERE folder_id = '{$folder->getId()}'
                    AND is_active = '1'
                ";
                // исключаем ранее включенные ноды.
                foreach ($used_nodes as $used_nodes_value) {
                    $sql .= " AND node_id != '{$used_nodes_value}'";
                }
                $sql .= ' ORDER BY position';
            } elseif ($folder->getHasInheritNodes()) { // в этой папке есть ноды, которые наследуются...
                $sql = "SELECT n.*
                    FROM {$this->db_prefix}engine_nodes AS n,
                        {$this->db_prefix}engine_blocks_inherit AS bi
                    WHERE n.block_id = bi.block_id
                        AND n.is_active = 1
                        AND n.folder_id = '{$folder->getId()}'
                        AND bi.folder_id = '{$folder->getId()}'
                    ORDER BY n.position ASC
                ";
            }

            // В папке нет нод для сборки.
            if ($sql === false) {
                continue;
            }

            $result = $this->db->query($sql);
            while ($row = $result->fetchObject()) {
                /*
                if ($this->Permissions->isAllowed('node', 'read', $row->permissions) == 0) {
                    continue;
                }*/

                // Создаётся список нод, которые уже в включены.
                if ($folder->getHasInheritNodes()) {
                    $used_nodes[] = $row->node_id;
                }

                $this->nodes[$row->node_id] = $row->node_id;
            }
        }

        foreach ($lockout_nodes['single'] as $node_id) {
            unset($this->nodes[$node_id]);
        }

        foreach ($lockout_nodes['inherit'] as $node_id) {
            unset($this->nodes[$node_id]);
        }

        if (!empty($lockout_nodes['except'])) {
            foreach ($this->nodes as $node_id) {
                if (!array_key_exists($node_id, $lockout_nodes['except'])) {
                    unset($this->nodes[$node_id]);
                }
            }
        }

        // Заполнение массива с нодами сущностями нод.
        foreach ($this->repository->findIn($this->nodes) as $node) {
            if (isset($router_data['node_routing']['controller'])
                and $router_data['node_routing']['node_id'] == $node->getId()
            ) {
                $node->setController($router_data['node_routing']['controller']);
                $node->setPriority(255);
            }

            $this->nodes[$node->getId()] = $node;
        }

        // @todo продумать в каком месте лучше кешировать ноды, также продумать инвалидацию.
        /*
        $is_cached = true;
        $cache = $this->container->get('cms.cache');
        $nodes = [];
        $list = '';
        foreach ($this->nodes as $node_id) {
            $list .= $node_id . ',';

            if ($cache->hasNode($node_id)) {
                $nodes[] = $cache->getNode($node_id);
            } else {
                $is_cached = false;
            }
        }

        if (strlen($list)) {
            if (!$is_cached) {
                $em = $this->container->get('doctrine')->getManager();
                $list = substr($list, 0, strlen($list)-1);
                $query = $em->createQuery("
                    SELECT n
                    FROM CMSBundle:Node n
                    WHERE n.node_id IN({$list})
                    ORDER BY n.position ASC
                ");
                $nodes = $query->getResult();
            }

            // Приведение массива в вид с индексами в качестве ID нод.
            foreach ($nodes as $node) {
                if (!$is_cached) {
                    $cache->setNode($node);
                }

                if (isset($router_data['node_route']['response']) and $router_data['node_route']['id'] == $node->getId()) {
                    $node->setRouterResponse($router_data['node_route']['response']);
                }

                $this->nodes[$node->getId()] = $node;
            }
        }
        */

        \Profiler::end('buildNodesList');

        if ($router_data['http_method'] == 'GET') {
            $this->tagcache->set($cache_key, $this->nodes, ['folder', 'node']);
        }

        return $this->nodes;
    }

    /**
     * @param Node $entity
     */
    public function remove(Node $entity)
    {
        $this->em->remove($entity);
        $this->em->flush($entity);
    }
}
