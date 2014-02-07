<?php

namespace SmartCore\Module\Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Blog\Model\Category as SmartCategory;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_categories")
 */
class Category extends SmartCategory
{

}
