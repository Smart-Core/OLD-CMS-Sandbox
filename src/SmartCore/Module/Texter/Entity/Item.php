<?php

namespace SmartCore\Module\Texter\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="text_items")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $item_id;
    
    /**
     * @ORM\Column(type="string", length=8, nullable=TRUE)
     */
    protected $language;
    
    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    protected $text;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $meta;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $datetime;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id = 0;
    
    public function __construct()
    {
        $this->datetime = new \DateTime();
        $this->language = 'ru';
        $this->meta = []; //new ArrayCollection();
        $this->text = null;
    }

    public function __toString()
    {
        return $this->getText();
    }

    public function getId()
    {
        return $this->item_id;
    }

    /**
     * Получить анонс.
     * @return string
     */
    public function getAnnounce()
    {
        $a = strip_tags($this->text);

        if (mb_strlen($a, 'utf-8') > 120) {
            $dotted = '...';
        } else {
            $dotted = '';
        }

        return mb_substr($a, 0, 120, 'utf-8') . $dotted;
    }
    
    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getMeta()
    {
        if ($this->meta) {
            return $this->meta;
        } else {
            return [];
        }
    }

    public function setMeta($meta)
    {
        if (is_array($meta)) {
            foreach($meta as $key => $value) {
                if (empty($value)) {
                    unset($meta[$key]);
                }
            }
        } else {
            $this->meta = [];
        }

        $this->meta = $meta;

        return $this;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }
}
