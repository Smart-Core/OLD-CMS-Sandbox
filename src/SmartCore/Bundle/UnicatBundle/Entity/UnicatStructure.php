<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unicat_structures")
 */
class UnicatStructure
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * single | multi
     *
     * @ORM\Column(type="string", length=16)
     */
    protected $entries;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $id_required;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var UnicatRepository
     *
     * @ORM\ManyToOne(targetEntity="UnicatRepository", inversedBy="structures")
     */
    protected $repository;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
