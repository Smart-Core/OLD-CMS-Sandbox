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
     * @ORM\Column(type="integer")
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
     * @todo @var PrepertyGroup
     */
    protected $group;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $params;

    /**
     * @var \SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository
     *
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository")
     **/
    protected $repository;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
