<?php

namespace SmartCore\Module\Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Blog\Model\Tag as SmartTag;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Blog\Repository\TagRepository")
 * @ORM\Table(name="blog_tags",
 *      indexes={
 *          @ORM\Index(name="weight", columns={"weight"})
 *      }
 * )
 */
class Tag extends SmartTag
{

}
