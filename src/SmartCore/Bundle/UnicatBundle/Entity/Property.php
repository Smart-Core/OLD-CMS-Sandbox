<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unicat_properties")
 */
class Property
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="is_enabled", type="boolean")
     *
     * @var boolean
     */
    protected $isEnabled;

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
     * @ORM\Column(name="show_in_admin", type="boolean")
     *
     * @var boolean
     */
    protected $showInAdmin;

    /**
     * Отображать в списке записей.
     *
     * @ORM\Column(name="show_in_list", type="boolean")
     *
     * @var boolean
     */
    protected $showInList;

    /**
     * Отображать при просмотре записи.
     *
     * @ORM\Column(name="show_in_view", type="boolean")
     *
     * @var boolean
     */
    protected $showInView;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $position;

    /**
     * @todo
     * @var PrepertyGroup
     */
    protected $group;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
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
