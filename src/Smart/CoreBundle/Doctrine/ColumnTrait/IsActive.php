<?php

namespace Smart\CoreBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * IsActive column
 */
trait IsActive
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":1})
     */
    protected $is_active;

    /**
     * @param bool $is_active
     * @return $this
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->is_active;
    }
}
