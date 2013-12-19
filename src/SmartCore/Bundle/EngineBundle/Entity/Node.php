<?php

namespace SmartCore\Bundle\EngineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SmartCore\Bundle\EngineBundle\Module\RouterResponse;

/**
 * @ORM\Entity(repositoryClass="NodeRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="engine_nodes",
 *      indexes={
 *          @ORM\Index(name="is_active", columns={"is_active"}),
 *          @ORM\Index(name="position",  columns={"position"}),
 *          @ORM\Index(name="block_id",  columns={"block_id"}),
 *          @ORM\Index(name="module",    columns={"module"})
 *      }
 * )
 */
class Node implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $node_id;

    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     * @var bool
     */
    protected $is_active;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @var string
     */
    protected $module;

    /**
     * @ORM\Column(type="array", nullable=FALSE)
     * @var array
     */
    protected $params;

    /**
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="nodes")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="folder_id")
     * @Assert\NotBlank()
     * @var Folder
     */
    protected $folder;

    /**
     * Хранение folder_id для минимизации кол-ва запросов.
     *
     * @var int|null
     */
    protected $folder_id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Block")
     * @ORM\JoinColumn(name="block_id", referencedColumnName="block_id")
     * @Assert\NotBlank()
     * @var Block
     */
    protected $block;

    /**
     * Позиция в внутри блока.
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @var int
     */
    protected $position;

    /**
     * Приоритет порядка выполнения.
     *
     * @ORM\Column(type="smallint")
     * @var int
     */
    protected $priority;

    /**
     * Может ли нода кешироваться.
     *
     * @ORM\Column(type="boolean", nullable=TRUE)
     * @var bool
     */
    protected $is_cached;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    //protected $cache_params;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    //protected $cache_params_yaml;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    //protected $plugins;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    //protected $permissions;

    /**
     * @ORM\Column(type="smallint")
     */
    //protected $database_id = 0;

    /**
     * @ORM\Column(type="string")
     */
    //protected $node_action_mode = 'popup';

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     * @var string
     */
    protected $descr;

    /**
     * @ORM\Column(type="integer")
     */
    protected $create_by_user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $create_datetime;

    /**
     * Ответ роутинга ноды, если таковой есть.
     *
     * @var RouterResponse|null
     */
    protected $router_response = null;

    protected $controller = null;
    protected $action = 'index';
    protected $arguments = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->create_by_user_id = 0;
        $this->create_datetime = new \DateTime();
        $this->is_active = true;
        $this->is_cached = false;
        $this->params = [];
        $this->position = 0;
        $this->priority = 0;
    }

    /**
     * Сериализация
     */
    public function serialize()
    {
        $this->getFolderId();
        $this->getBlock()->getId();
        return serialize([
            //return igbinary_serialize([
            $this->node_id,
            $this->is_active,
            $this->is_cached,
            $this->module,
            $this->params,
            $this->folder,
            $this->folder_id,
            $this->block,
            $this->position,
            $this->priority,
            $this->descr,
            $this->create_by_user_id,
            $this->create_datetime,
        ]);
    }

    /**
     * @param string $serialized
     * @return mixed|void
     */
    public function unserialize($serialized)
    {
        list(
            $this->node_id,
            $this->is_active,
            $this->is_cached,
            $this->module,
            $this->params,
            $this->folder,
            $this->folder_id,
            $this->block,
            $this->position,
            $this->priority,
            $this->descr,
            $this->create_by_user_id,
            $this->create_datetime,
            ) = unserialize($serialized);
        //) = igbinary_unserialize($serialized);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->node_id;
    }

    /**
     * @param int $create_by_user_id
     */
    public function setCreateByUserId($create_by_user_id)
    {
        $this->create_by_user_id = $create_by_user_id;
    }

    /**
     * @return int
     */
    public function getCreateByUserId()
    {
        return $this->create_by_user_id;
    }

    /**
     * @param bool $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_cached
     */
    public function setIsCached($is_cached)
    {
        $this->is_cached = $is_cached;
    }

    /**
     * @return bool
     */
    public function getIsCached()
    {
        return $this->is_cached;
    }

    /**
     * @param string $descr
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    /**
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        if (empty($position)) {
            $position = 0;
        }

        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param Block $block
     */
    public function setBlock(Block $block)
    {
        $this->block = $block;
    }

    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param Folder $folder
     */
    public function setFolder(Folder $folder)
    {
        $this->folder = $folder;
    }

    /**
     * @return Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        if (empty($this->params)) {
            return [];
        } else {
            return $this->params;
        }
    }

    /**
     * @return int
     */
    public function getFolderId()
    {
        if ($this->folder_id == null) {
            $this->folder_id = $this->getFolder()->getId();
        }

        return $this->folder_id;
    }

    /**
     * @param RouterResponse $router_response
     */
    public function setRouterResponse(RouterResponse $router_response)
    {
        $this->setController($router_response->getController());
        $this->setAction($router_response->getAction());
        $this->setArguments($router_response->getAllArguments());

        $this->router_response = $router_response;
    }

    /**
     * @return null|RouterResponse
     */
    public function getRouterResponse()
    {
        return $this->router_response;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController()
    {
        if (!empty($this->controller)) {
            return $this->controller;
        } else {
            return $this->module;
        }
    }
}
