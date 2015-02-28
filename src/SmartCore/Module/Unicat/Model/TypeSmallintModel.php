<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * {@inheritDoc}
 */
abstract class TypeSmallintModel extends AbstractType
{
    /**
     * @ORM\Column(type="smallint")
     */
    protected $value;
}
