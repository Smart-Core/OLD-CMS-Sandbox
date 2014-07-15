<?php

namespace SmartCore\Bundle\CMSBundle\Model;

trait IsEnabledTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_enabled = true;

    /**
     * @param bool $is_enabled
     * @return $this
     */
    public function setIsEnabled($is_enabled)
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return !$this->is_enabled;
    }
}
