<?php

namespace SmartCore\Module\WebForm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="webforms")
 */
class WebForm
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\NameUnique;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\UserId;

    /**
     * @var WebFormField[]
     *
     * @ORM\OneToMany(targetEntity="WebFormField", mappedBy="web_form")
     */
    protected $fields;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->fields       = new ArrayCollection();
    }

    /**
     * @return WebFormField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param WebFormField[] $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }
}
