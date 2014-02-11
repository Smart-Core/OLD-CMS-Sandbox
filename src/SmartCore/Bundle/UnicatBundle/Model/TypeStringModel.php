<?php

namespace SmartCore\Bundle\UnicatBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * {@inheritDoc}
 */
abstract class TypeStringModel extends AbstractType
{
    /**
     * @ORM\Column(type="string")
     */
    protected $value;
}
