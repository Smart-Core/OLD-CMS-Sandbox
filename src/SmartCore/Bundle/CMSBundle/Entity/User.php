<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\UserModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="users2",
 *      indexes={
 *          @ORM\Index(columns={"firstname"}),
 *          @ORM\Index(columns={"lastname"})
 *      }
 * )
 */
class User extends UserModel
{
}
