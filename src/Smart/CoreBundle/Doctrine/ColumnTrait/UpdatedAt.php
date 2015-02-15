<?php

namespace Smart\CoreBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * UpdatedAt column
 */
trait UpdatedAt
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     * @param \DateTime $updated_at
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updated_at = null)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
