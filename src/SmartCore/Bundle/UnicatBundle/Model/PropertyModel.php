<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_properties")
 *
 * @UniqueEntity(fields={"name"}, message="Имя свойства должно быть уникальным.")
 */
class PropertyModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_enabled;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_dedicated_table;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_required;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * enum('string','text','date','datetime','img','file','select','multiselect','int','double','checkbox','password')
     *
     * @ORM\Column(type="string", length=10)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * Отображать в списке администратора.
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $show_in_admin;

    /**
     * Отображать в списке записей.
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $show_in_list;

    /**
     * Отображать при просмотре записи.
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $show_in_view;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $params;

    /**
     * @var PropertyGroupModel
     *
     * @ORM\ManyToOne(targetEntity="PropertyGroup", inversedBy="properties")
     */
    protected $group;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->params = [];
        $this->position = 0;
        $this->user_id = 0;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param bool $is_dedicated_table
     * @return $this
     */
    public function setIsDedicatedTable($is_dedicated_table)
    {
        $this->is_dedicated_table = $is_dedicated_table;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDedicatedTable()
    {
        return $this->is_dedicated_table;
    }

    /**
     * @param bool $is_enabled
     * @return $this
     */
    public function setIsEnabled($is_enabled)
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * @param boolean $is_required
     * @return $this
     */
    public function setIsRequired($is_required)
    {
        $this->is_required = $is_required;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRequired()
    {
        return $this->is_required;
    }

    /**
     * @param \SmartCore\Bundle\UnicatBundle\Model\PropertyGroupModel $group
     * @return $this
     */
    public function setGroup(PropertyGroupModel $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return \SmartCore\Bundle\UnicatBundle\Model\PropertyGroupModel
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params = null)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        if (null === $position) {
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
     * @param boolean $show_in_admin
     * @return $this
     */
    public function setShowInAdmin($show_in_admin)
    {
        $this->show_in_admin = $show_in_admin;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowInAdmin()
    {
        return $this->show_in_admin;
    }

    /**
     * @param boolean $show_in_list
     * @return $this
     */
    public function setShowInList($show_in_list)
    {
        $this->show_in_list = $show_in_list;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowInList()
    {
        return $this->show_in_list;
    }

    /**
     * @param boolean $show_in_view
     * @return $this
     */
    public function setShowInView($show_in_view)
    {
        $this->show_in_view = $show_in_view;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowInView()
    {
        return $this->show_in_view;
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
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isType($type)
    {
        return ($type === $this->type) ? true : false;
    }

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
