<?php

namespace Smart\CoreBundle\Doctrine\ColumnTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * Id column
 * @author Gusakov Nikita <dev@nkt.me>
 */
trait Id
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
