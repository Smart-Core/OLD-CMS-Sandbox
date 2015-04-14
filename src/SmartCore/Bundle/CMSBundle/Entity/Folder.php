<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FolderRepository")
 * @ORM\Table(name="engine_folders",
 *      indexes={
 *          @ORM\Index(columns={"is_active"}),
 *          @ORM\Index(columns={"is_deleted"}),
 *          @ORM\Index(columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"folder_pid", "uri_part"}),
 *      }
 * )
 * @UniqueEntity(fields={"uri_part", "parent_folder"}, message="в каждой подпапке должен быть уникальный сегмент URI")
 */
class Folder
{
    use ColumnTrait\Id;
    use ColumnTrait\IsActive;
    use ColumnTrait\IsDeleted;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\DeletedAt;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\UserId;

    /**
     * @var Folder
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="children")
     * @ORM\JoinColumn(name="folder_pid")
     */
    protected $parent_folder;

    /**
     * @var Folder[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="parent_folder")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @var Node[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Node", mappedBy="folder")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $nodes;

    /**
     * @var Region[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Region", mappedBy="folders", fetch="EXTRA_LAZY")
     */
    protected $regions;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $uri_part;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_file;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $meta;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $redirect_to;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @todo можно сделать через связь
     */
    protected $router_node_id;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $permissions;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $lockout_nodes;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $template_inheritable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $template_self;

    /**
     * Для отображения в формах. Не маппится в БД.
     */
    protected $form_title = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->children             = new ArrayCollection();
        $this->created_at           = new \DateTime();
        $this->is_active            = true;
        $this->is_deleted           = false;
        $this->is_file              = false;
        $this->lockout_nodes        = null;
        $this->meta                 = [];
        $this->nodes                = new ArrayCollection();
        $this->parent_folder        = null;
        $this->permissions          = null;
        $this->position             = 0;
        $this->regions              = new ArrayCollection();
        $this->redirect_to          = null;
        $this->router_node_id       = null;
        $this->template_inheritable = null;
        $this->template_self        = null;
        $this->uri_part             = null;
        $this->user_id              = 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return Folder[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Node[]|ArrayCollection
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param bool $is_file
     * @return $this
     */
    public function setIsFile($is_file)
    {
        $this->is_file = $is_file;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsFile()
    {
        return $this->is_file;
    }

    /**
     * @param string $uri_part
     * @return $this
     */
    public function setUriPart($uri_part)
    {
        $this->uri_part = $uri_part;

        return $this;
    }

    /**
     * @return string
     */
    public function getUriPart()
    {
        return $this->uri_part;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        foreach ($meta as $name => $value) {
            if (empty($value)) {
                unset($meta[$name]);
            }
        }

        $this->meta = $meta;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return empty($this->meta) ? [] : $this->meta;
    }

    /**
     * @param Folder $parent_folder
     * @return $this
     */
    public function setParentFolder(Folder $parent_folder)
    {
        $this->parent_folder = ($this->getId() == 1) ? null : $parent_folder;

        return $this;
    }

    /**
     * @return Folder|null
     */
    public function getParentFolder()
    {
        return $this->parent_folder;
    }

    /**
     * @param string $form_title
     * @return $this
     */
    public function setFormTitle($form_title)
    {
        $this->form_title = $form_title;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormTitle()
    {
        return $this->form_title;
    }

    /**
     * @param int|null $router_node_id
     * @return $this
     */
    public function setRouterNodeId($router_node_id)
    {
        $this->router_node_id = empty($router_node_id) ? null : $router_node_id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRouterNodeId()
    {
        return $this->router_node_id;
    }

    /**
     * @param string $template_inheritable
     * @return $this
     */
    public function setTemplateInheritable($template_inheritable)
    {
        $this->template_inheritable = $template_inheritable;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateInheritable()
    {
        return $this->template_inheritable;
    }

    /**
     * @param string $template_self
     * @return $this
     */
    public function setTemplateSelf($template_self)
    {
        $this->template_self = $template_self;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateSelf()
    {
        return $this->template_self;
    }

    /**
     * @return Region[]|ArrayCollection
     */
    public function getRegions()
    {
        return $this->regions;
    }

    /**
     * @param Region[]|ArrayCollection $regions
     *
     * @return $this
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;

        return $this;
    }
}
