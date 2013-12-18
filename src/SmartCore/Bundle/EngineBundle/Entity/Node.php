<?php

namespace SmartCore\Bundle\EngineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SmartCore\Bundle\EngineBundle\Module\RouterResponse;
use SmartCore\Bundle\EngineBundle\Container;

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
     */
    protected $node_id;

    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $is_active;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $module;

    /**
     * @ORM\Column(type="array", nullable=FALSE)
     */
    protected $params;

    /**
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="nodes")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="folder_id")
     * @Assert\NotBlank()
     */
    protected $folder;

    /**
     * Хранение folder_id для минимизации кол-ва запросов.
     */
    protected $folder_id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Block")
     * @ORM\JoinColumn(name="block_id", referencedColumnName="block_id")
     * @Assert\NotBlank()
     */
    protected $block;

    /**
     * Позиция в внутри блока.
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $position;

    /**
     * Приоритет порядка выполнения.
     *
     * @ORM\Column(type="smallint")
     */
    protected $priority;

    /**
     * Может ли нода кешироваться.
     *
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $is_cached;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    //protected $cache_params;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     * 
     * @todo !!! Убрать !!! это временное поле...
     */
    //protected $cache_params_yaml;

    /**
     * @todo пересмотреть.
     * 
     * @ORM\Column(type="text", nullable=TRUE)
     */
    //protected $plugins;

    /**
     * @todo пересмотреть.
     * 
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
     * @var RouterResponse|null
     */
    protected $router_response = null;

    protected $controller = null;
    protected $action = 'index';
    protected $arguments = [];

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

    public function getId()
    {
        return $this->node_id;
    }

    public function setCreateByUserId($create_by_user_id)
    {
        $this->create_by_user_id = $create_by_user_id;
    }

    public function getCreateByUserId()
    {
        return $this->create_by_user_id;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setIsCached($is_cached)
    {
        $this->is_cached = $is_cached;
    }

    public function getIsCached()
    {
        return $this->is_cached;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function setPosition($position)
    {
        if (empty($position)) {
            $position = 0;
        }

        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setBlock($block)
    {
        $this->block = $block;
    }

    public function getBlock()
    {
        return $this->block;
    }

    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

    public function getFolder()
    {
        return $this->folder;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        if (empty($this->params)) {
            return [];
        } else {
            return $this->params;
        }
    }
    
    public function getFolderId()
    {
        if ($this->folder_id == null) {
            $this->folder_id = $this->getFolder()->getId();
        }

        return $this->folder_id;
    }

    public function setRouterResponse(RouterResponse $router_response)
    {
        $this->setController($router_response->getController());
        $this->setAction($router_response->getAction());
        $this->setArguments($router_response->getAllArguments());

        $this->router_response = $router_response;
    }

    public function getRouterResponse()
    {
        return $this->router_response;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getController()
    {
        if (!empty($this->controller)) {
            return $this->controller;
        } else {
            return $this->module;
        }
    }
}
