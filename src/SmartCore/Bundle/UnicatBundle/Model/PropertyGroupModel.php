<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * @ORM\Table(name="unicat_properties_groups")
 */
class PropertyGroupModel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * Constructor.
     */
    public function __construct()
    {

    }
}
