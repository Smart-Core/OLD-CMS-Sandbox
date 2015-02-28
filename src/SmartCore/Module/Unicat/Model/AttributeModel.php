<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Yaml\Yaml;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_attributes")
 *
 * @UniqueEntity(fields={"name"}, message="Имя свойства должно быть уникальным.")
 */
class AttributeModel
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Position;
    use ColumnTrait\NameUnique;
    use ColumnTrait\Title;
    use ColumnTrait\UserId;

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
     * enum('string','text','date','datetime','img','file','select','multiselect','int','double','checkbox','password')
     *
     * @ORM\Column(type="string", length=12)
     */
    protected $type;

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
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $params;

    /**
     * @var array
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $params_yaml;

    /**
     * @var AttributesGroupModel
     *
     * @ORM\ManyToOne(targetEntity="AttributesGroup", inversedBy="attributes")
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
        $this->params_yaml = null;
        $this->position = 0;
        $this->user_id = 0;
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
     * @param AttributesGroupModel $group
     * @return $this
     */
    public function setGroup(AttributesGroupModel $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return AttributesGroupModel
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return (null == $this->params) ? [] : $this->params;
    }

    /**
     * @param array $params_yaml
     * @return $this
     */
    public function setParamsYaml($params_yaml)
    {
        $this->params_yaml = $params_yaml;

        $params = Yaml::parse($params_yaml);

        if (empty($params)) {
            $params = [];
        }

        $this->setParams($params);

        return $this;
    }

    /**
     * @return array
     */
    public function getParamsYaml()
    {
        return $this->params_yaml;
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
}
