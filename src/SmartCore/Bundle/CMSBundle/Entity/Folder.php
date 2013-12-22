<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\CMSBundle\Entity\Node;

/**
 * @ORM\Entity(repositoryClass="FolderRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="engine_folders",
 *      indexes={
 *          @ORM\Index(name="is_active", columns={"is_active"}),
 *          @ORM\Index(name="is_deleted", columns={"is_deleted"}),
 *          @ORM\Index(name="position", columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="folder_pid_uri_part", columns={"folder_pid", "uri_part"}),
 *      }
 * )
 * @UniqueEntity(fields={"uri_part", "parent_folder"}, message="в каждой подпапке должен быть уникальный сегмент URI")
 */
class Folder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $folder_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="children")
     * @ORM\JoinColumn(name="folder_pid", referencedColumnName="folder_id")
     */
    protected $parent_folder;

    /**
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="parent_folder")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="folder")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $nodes;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $is_file;

    /**
     * @ORM\Column(type="smallint", nullable=TRUE)
     */
    protected $position;
    
    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $uri_part;
    
    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $is_active;
    
    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $is_deleted;
    
    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $descr;
    
    /**
     * @ORM\Column(type="array", nullable=TRUE)
     */
    protected $meta;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $redirect_to;
    
    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    protected $router_node_id;
    
    /**
     * @ORM\Column(type="boolean", nullable=TRUE)
     */
    protected $has_inherit_nodes;
    
    /**
     * @ORM\Column(type="array", nullable=TRUE)
     */
    protected $permissions;

    /**
     * @ORM\Column(type="array", nullable=TRUE)
     */
    protected $lockout_nodes;

    /**
     * @ORM\Column(type="string", length=30, nullable=TRUE)
     */
    protected $template;

    /**
     * @ORM\Column(type="integer")
     */
    protected $create_by_user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $create_datetime;

    /**
     * Для отображения в формах. Не маппится в БД.
     */
    protected $form_title;

    /**
     * Полный URI. Генерируется динамически.
     */
    protected $uri;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->create_by_user_id = 0;
        $this->create_datetime = new \DateTime();
        $this->form_title = '';
        $this->meta = null;
        $this->is_active = true;
        $this->is_deleted = false;
        $this->is_file = false;
        $this->has_inherit_nodes = false;
        $this->lockout_nodes = null;
        $this->nodes = new ArrayCollection();
        $this->parent_folder = null;
        $this->permissions = null;
        $this->position = 0;
        $this->redirect_to = null;
        $this->router_node_id = null;
        $this->template = null;
        $this->uri_part = '';
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getNodes()
    {
        return $this->nodes;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setIsFile($is_file)
    {
        $this->is_file = $is_file;
    }

    public function getIsFile()
    {
        return $this->is_file;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setHasInheritNodes($has_inherit_nodes)
    {
        $this->has_inherit_nodes = $has_inherit_nodes;
    }

    public function getHasInheritNodes()
    {
        return $this->has_inherit_nodes;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function setUriPart($uri_part)
    {
        $this->uri_part = $uri_part;
    }

    public function getUriPart()
    {
        return $this->uri_part;
    }

    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    public function getMeta()
    {
        if (empty($this->meta)) {
            return [];
        } else {
            return $this->meta;
        }
    }

    public function getId()
    {
        return $this->folder_id;
    }

    public function setCreateByUserId($create_by_user_id)
    {
        $this->create_by_user_id = $create_by_user_id;
    }

    public function getCreateByUserId()
    {
        return $this->create_by_user_id;
    }

    public function setParentFolder($parent_folder)
    {
        if ($this->getId() == 1) {
            $this->parent_folder = null;
        } else {
            $this->parent_folder = $parent_folder;
        }
    }

    public function getParentFolder()
    {
        return $this->parent_folder;
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

    public function setFormTitle($form_title)
    {
        $this->form_title = $form_title;
    }

    public function getFormTitle()
    {
        return $this->form_title;
    }

    public function setRouterNodeId($router_node_id)
    {
        $this->router_node_id = $router_node_id;
    }

    public function getRouterNodeId()
    {
        return $this->router_node_id;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function checkRelations()
    {
        if (empty($this->uri_part) and $this->getId() != 1) {
            $this->setUriPart($this->getId());
        }

        // Защита от цикличных зависимостей.
        $parent = $this->getParentFolder();

        if(null == $parent) {
            return;
        }

        $cnt = 30;
        $ok = false;
        while ($cnt--) {
            if ($parent->getId() == 1) {
                $ok = true;
                break;
            } else {
                $parent = $parent->getParentFolder();
                continue;
            }
        }

        // Если обнаружена циклическая зависимость, тогда родитель выставляется корневая папка, которая имеет id = 1.
        if (!$ok) {
            $this->setParentFolder(Container::get('cms.folder')->get(1));
        }
    }
}
