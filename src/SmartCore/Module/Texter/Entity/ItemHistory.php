<?php

namespace SmartCore\Module\Texter\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="text_items_history",
 *         indexes={
 *             @ORM\Index(name="item_id", columns={"item_id"}),
 *             @ORM\Index(name="is_deleted", columns={"is_deleted"}),
 *         }
 * )
 *
 * @todo сделать.
 */
class ItemHistory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $history_id;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_deleted = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $item_id;
    
    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $language;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $text_archive;

    /**
     * @ORM\Column(type="text")
     */
    protected $meta_archive;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $update_datetime;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id = 0;

}
