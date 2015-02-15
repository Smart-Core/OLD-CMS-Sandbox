<?php

namespace Smart\CoreBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * Name column
 * @author Gusakov Nikita <dev@nkt.me>
 */
trait Name
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @see getName
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
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
     *
     * @return static
     *
     * @todo http://php.net/manual/ru/ref.mbstring.php
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
