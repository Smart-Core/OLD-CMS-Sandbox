<?php

namespace SmartCore\Bundle\Unicat2Bundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * {@inheritDoc}
 */
abstract class TypeIntModel extends AbstractType
{
    /**
     * @ORM\Column(type="integer")
     */
    protected $value;
}
