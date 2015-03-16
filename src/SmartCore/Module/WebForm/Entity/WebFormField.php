<?php

namespace SmartCore\Module\WebForm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Yaml\Yaml;

/**
 * @ORM\Entity()
 * @ORM\Table(name="webforms_fields")
 * @UniqueEntity(fields={"name", "web_form"}, message="Имя свойства должно быть уникальным.")
 */
class WebFormField
{
    use ColumnTrait\Id;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Position;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\UserId;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    protected $is_required;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern="/^[a-z_]+$/",
     *      htmlPattern="^[a-z_]+$",
     *      message="Имя может состоять только из латинских букв в нижнем регистре и символов подчеркивания."
     * )
     *
     * @todo перевод сообщения
     */
    protected $name;

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
     * enum('string','text','date','datetime','img','file','select','multiselect','int','double','checkbox','password')
     *
     * @ORM\Column(type="string", length=12)
     */
    protected $type;

    /**
     * @var WebForm
     *
     * @ORM\ManyToOne(targetEntity="WebForm", inversedBy="fields")
     */
    protected $web_form;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->is_enabled   = true;
        $this->is_required  = false;
        $this->params       = [];
        $this->params_yaml  = null;
        $this->position     = 0;
        $this->user_id      = 0;
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
     * @return array
     */
    public function getParam($name)
    {
        if (!empty($this->params) and isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return [];
        }
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
     * @return WebForm
     */
    public function getWebForm()
    {
        return $this->web_form;
    }

    /**
     * @param WebForm $web_form
     * @return $this
     */
    public function setWebForm($web_form)
    {
        $this->web_form = $web_form;

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
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
