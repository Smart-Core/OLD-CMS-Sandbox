<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_categories"
 *      indexes={
 *          @ORM\Index(name="is_enabled", columns={"is_enabled"}),
 *          @ORM\Index(name="position", columns={"position"})
 *      }
 * )
 *
 * @UniqueEntity(fields={"slug", "parent"}, message="В каждой подкатегории должен быть уникальный сегмент URI")
 */
abstract class CategoryModel
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
     * @var CategoryModel
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", cascade={"persist"})
     **/
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="categories")
     */
    protected $items;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_inheritance;

    /**
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->is_inheritance = true;
        $this->meta = null;
        $this->position = 0;
        $this->user_id = 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
