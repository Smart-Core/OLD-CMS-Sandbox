<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use SmartCore\Bundle\CMSBundle\Tools\FrontControl;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NodeRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="engine_nodes",
 *      indexes={
 *          @ORM\Index(columns={"is_active"}),
 *          @ORM\Index(columns={"is_deleted"}),
 *          @ORM\Index(columns={"position"}),
 *          @ORM\Index(columns={"region_id"}),
 *          @ORM\Index(columns={"module"})
 *      }
 * )
 */
class Node implements \Serializable
{
    // Получать элементы управления для тулбара.
    const TOOLBAR_NO                    = 0; // Никогда
    const TOOLBAR_ONLY_IN_SELF_FOLDER   = 1; // Только в собственной папке
    const TOOLBAR_ALWAYS                = 2; // Всегда

    use ColumnTrait\Id;
    use ColumnTrait\IsActive;
    use ColumnTrait\IsDeleted;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\DeletedAt;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\UserId;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $controls_in_toolbar;

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
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region", fetch="EAGER")
     * @Assert\NotBlank()
     */
    protected $region;

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
     * @ORM\Column(type="boolean")
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

    // ================================= Unmapped properties =================================

    /**
     * @var array
     */
    protected $controller = [];

    /**
     * Edit-In-Place.
     *
     * @var bool
     */
    protected $eip = false;

    /**
     * @var FrontControl[]
     */
    protected $front_controls = [];

    /**
     * @var string
     */
    protected $region_name = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->controls_in_toolbar = self::TOOLBAR_ONLY_IN_SELF_FOLDER;
        $this->created_at   = new \DateTime();
        $this->is_active    = true;
        $this->is_cached    = false;
        $this->is_deleted   = false;
        $this->params       = [];
        $this->position     = 0;
        $this->priority     = 0;
        $this->user_id      = 1;
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
            $this->is_deleted,
            $this->module,
            $this->params,
            $this->folder,
            $this->folder_id,
            $this->region,
            $this->region_name,
            $this->position,
            $this->priority,
            $this->template,
            $this->description,
            $this->controls_in_toolbar,
            $this->user_id,
            $this->created_at,
            $this->controller,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->is_active,
            $this->is_cached,
            $this->is_deleted,
            $this->module,
            $this->params,
            $this->folder,
            $this->folder_id,
            $this->region,
            $this->region_name,
            $this->position,
            $this->priority,
            $this->template,
            $this->description,
            $this->controls_in_toolbar,
            $this->user_id,
            $this->created_at,
            $this->controller) = unserialize($serialized);
        //) = igbinary_unserialize($serialized);
    }

    /**
     * @param int $controls_in_toolbar
     *
     * @return $this
     */
    public function setControlsInToolbar($controls_in_toolbar)
    {
        $this->controls_in_toolbar = $controls_in_toolbar;

        return $this;
    }

    /**
     * @return int
     */
    public function getControlsInToolbar()
    {
        return $this->controls_in_toolbar;
    }

    /**
     * @param bool $is_cached
     *
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
     * @param Region $region
     *
     * @return $this
     */
    public function setRegion(Region $region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getRegionName()
    {
        if (null === $this->region_name) {
            $this->region_name = $this->getRegion()->getName();
        }

        return $this->region_name;
    }

    /**
     * @param Folder $folder
     *
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
     *
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
     *
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
     *
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        return (isset($this->params[$key])) ? $this->params[$key] : $default;
    }

    /**
     * @param int $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        if (empty($priority)) {
            $priority = 0;
        }

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
     *
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
     *
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
     * @param string $name
     *
     * @return FrontControl
     *
     * @throws \Exception
     */
    public function addFrontControl($name)
    {
        if (isset($this->front_controls[$name])) {
            throw new \Exception("From control: '{$name}' already exists.");
        }

        $this->front_controls[$name] = new FrontControl();
        $this->front_controls[$name]->setDescription($this->getDescription());

        return $this->front_controls[$name];
    }

    /**
     * @param array $front_controls
     *
     * @return $this
     *
     * @deprecated
     */
    public function setFrontControls($front_controls)
    {
        if ($this->isEip()) {
            $this->front_controls = $front_controls;
        }

        return $this;
    }

    /**
     * @return FrontControl[]
     */
    public function getFrontControls()
    {
        $data = [];

        if ($this->isEip()) {
            foreach ($this->front_controls as $name => $control) {
                $data[$name] = $control->getData();
            }
        }

        return $data;
    }

    /**
     * @param string $controller
     *
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
                '_controller' => $this->module.'Module:'.$className.':'.$actionName,
            ];
        }

        if (empty($this->controller)) {
            $className = (null === $controllerName) ? $this->module : $controllerName;
            $this->controller['_controller'] = $this->module.'Module:'.$className.':'.$actionName;
        }

        return $this->controller;
    }
}
