<?php

namespace SmartCore\Module\Texter\Entity;

use Doctrine\ORM\Mapping as ORM;

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

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->datetime = new \DateTime();
        $this->language = 'ru';
        $this->meta = [];
        $this->text = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getText();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->item_id;
    }

    /**
     * Получить анонс.
     *
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

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        if ($this->meta) {
            return $this->meta;
        } else {
            return [];
        }
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
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

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
