<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_properties")
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
     * enum('string','text','date','datetime','img','file','select','multiselect','int','double','checkbox','password')
     *
     * @ORM\Column(type="string", length=10)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=32)
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
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $position;

    /**
     * @todo
     * @var PrepertyGroup
     */
    protected $group;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     *
     * @todo
     */
    protected $validators;

    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
