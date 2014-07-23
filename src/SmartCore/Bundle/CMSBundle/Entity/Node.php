<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NodeRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="engine_nodes",
 *      indexes={
 *          @ORM\Index(name="is_active_engine_nodes", columns={"is_active"}),
 *          @ORM\Index(name="position_engine_nodes",  columns={"position"}),
 *          @ORM\Index(name="block_id_engine_nodes",  columns={"block_id"}),
 *          @ORM\Index(name="module_engine_nodes",    columns={"module"})
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
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $module;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    protected $params;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $template;

    /**
     * @var Folder
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="nodes")
     * @Assert\NotBlank()
     */
    protected $folder;

    /**
     * Хранение folder_id для минимизации кол-ва запросов.
     *
     * @var int|null
     */
    protected $folder_id = null;

    /**
     * @var Block
     *
     * @ORM\ManyToOne(targetEntity="Block", fetch="EAGER")
     * @Assert\NotBlank()
     */
    protected $block;

    /**
     * Позиция в внутри блока.
     *
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $position;

    /**
     * Приоритет порядка выполнения.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $priority;

    /**
     * Может ли нода кешироваться.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_cached;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    //protected $cache_params;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    //protected $plugins;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    //protected $permissions;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $descr;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $create_by_user_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $create_datetime;

    /**
     * @var array
     */
    protected $controller = [];

    /**
     * Edit-In-Place
     *
     * @var bool
     */
    protected $eip = false;

    /**
     * @var array
     */
    protected $front_controls = [];

    /**
     * @var string
     */
    protected $block_name = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->create_by_user_id = 0;
        $this->create_datetime   = new \DateTime();
        $this->is_active         = true;
        $this->is_cached         = false;
        $this->params            = [];
        $this->position          = 0;
        $this->priority          = 0;
    }

    /**
     * Сериализация.
     */
    public function serialize()
    {
        $this->getFolderId();

        return serialize([
            //return igbinary_serialize([
            $this->id,
            $this->is_active,
            $this->is_cached,
            $this->module,
            $this->params,
            $this->folder,
            $this->folder_id,
            $this->block,
            $this->block_name,
            $this->position,
            $this->priority,
            $this->descr,
            $this->create_by_user_id,
            $this->create_datetime,
            $this->controller,
        ]);
    }

    /**
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->is_active,
            $this->is_cached,
            $this->module,
            $this->params,
            $this->folder,
            $this->folder_id,
            $this->block,
            $this->block_name,
            $this->position,
            $this->priority,
            $this->descr,
            $this->create_by_user_id,
            $this->create_datetime,
            $this->controller,
            ) = unserialize($serialized);
        //) = igbinary_unserialize($serialized);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->is_active;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->is_active ? false : true;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $create_by_user_id
     * @return $this
     */
    public function setCreateByUserId($create_by_user_id)
    {
        $this->create_by_user_id = $create_by_user_id;

        return $this;
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
     * @return $this
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;

        return $this;
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
     * @return $this
     */
    public function setIsCached($is_cached)
    {
        $this->is_cached = $is_cached;

        return $this;
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
     * @return $this
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
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
     * @return $this
     */
    public function setPosition($position)
    {
        if (empty($position)) {
            $position = 0;
        }

        $this->position = $position;

        return $this;
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
     * @return $this
     */
    public function setBlock(Block $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        if (null === $this->block_name) {
            $this->block_name = $this->getBlock()->getName();
        }

        return $this->block_name;
    }

    /**
     * @param Folder $folder
     * @return $this
     */
    public function setFolder(Folder $folder)
    {
        $this->folder = $folder;

        return $this;
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
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
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
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return (empty($this->params)) ? [] : $this->params;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        return (isset($this->params[$key])) ? $this->params[$key] : $default;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate($default = null)
    {
        return empty($this->template) ? $default : $this->template;
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
     * @param bool $eip
     * @return $this
     */
    public function setEip($eip)
    {
        $this->eip = $eip;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEip()
    {
        return $this->eip;
    }

    /**
     * @return bool
     */
    public function isEip()
    {
        return $this->eip;
    }

    /**
     * @param array $front_controls
     * @return $this
     */
    public function addFrontControl($name, $controls)
    {
        if ($this->isEip()) {
            $this->front_controls[$name] = $controls;
        }

        return $this;
    }

    /**
     * @param array $front_controls
     * @return $this
     */
    public function setFrontControls($front_controls)
    {
        if ($this->isEip()) {
            $this->front_controls = $front_controls;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFrontControls()
    {
        return $this->front_controls;
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @return array
     */
    public function getControllerParams()
    {
        $params = [];
        foreach ($this->controller as $key => $val) {
            if ($key !== '_controller' and $key !== '_route') {
                $params[$key] = $val;
            }
        }

        return $params;
    }

    /**
     * @todo Продумать где подменять action у нод.
     *
     * @return array
     */
    public function getController($controllerName = null, $actionName = 'index')
    {
        if (null !== $controllerName or 'index' !== $actionName) {
            $className = (null === $controllerName) ? $this->module : $controllerName;

            return [
                '_controller' => $this->module . 'Module:' . $className . ':' . $actionName,
            ];
        }

        if (empty($this->controller)) {
            $className = (null === $controllerName) ? $this->module : $controllerName;
            $this->controller['_controller'] = $this->module . 'Module:' . $className . ':' . $actionName;
        }

        return $this->controller;
    }
}
