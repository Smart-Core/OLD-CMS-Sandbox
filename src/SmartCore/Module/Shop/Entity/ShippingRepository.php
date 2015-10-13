<?php

namespace SmartCore\Module\Shop\Entity;

use Doctrine\ORM\EntityRepository;
use Smart\CoreBundle\Doctrine\RepositoryTrait;

class ShippingRepository extends EntityRepository
{
    use RepositoryTrait\FindByQuery;
}
