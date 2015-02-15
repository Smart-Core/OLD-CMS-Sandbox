<?php

namespace Smart\CoreBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * CreatedAt column
 */
trait CreatedAt
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @param \DateTime $created_at
     *
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
