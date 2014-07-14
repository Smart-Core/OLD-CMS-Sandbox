<?php

namespace SmartCore\Bundle\CMSBundle\Model;

trait PositionTrait
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $position = 0;

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
