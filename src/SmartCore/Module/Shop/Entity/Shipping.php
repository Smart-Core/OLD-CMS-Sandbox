<?php

namespace SmartCore\Module\Shop\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Shop\Entity\ShippingRepository")
 * @ORM\Table(name="shop_shippings",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      }
 * )
 */
class Shipping
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\FosUser;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\Description;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
    }
}
