<?php

namespace SmartCore\Bundle\CMSBundle\Model;

trait UpdatedAtTrait
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
    public function setUpdatedAt(\DateTime $updated_at)
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
