<?php

namespace SandboxSiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\UserModel;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="users",
 *      indexes={
 *          @ORM\Index(columns={"firstname"}),
 *          @ORM\Index(columns={"lastname"}),
 *          @ORM\Index(columns={"patronymic"})
 *      }
 * )
 */
class User extends UserModel
{
    use ColumnTrait\Phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $patronymic;

    /**
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * @param string $patronymic
     *
     * @return $this
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }
}
