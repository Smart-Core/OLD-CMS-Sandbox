<?php

namespace SmartCore\Module\Texter\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="texter")
 */
class Item
{
    use CreatedAtTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $locale;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $editor;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $user_id = 0;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->locale   = 'ru';
        $this->meta     = [];
        $this->text     = null;
        $this->editor   = 1;
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
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAnnounce()
    {
        $a = strip_tags($this->text);

        $dotted = (mb_strlen($a, 'utf-8') > 100) ? '...' : '';

        return mb_substr($a, 0, 100, 'utf-8').$dotted;
    }

    /**
     * @param int $editor
     * @return $this
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * @return int
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
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
        return empty($this->meta) ? [] : $this->meta;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        if (is_array($meta)) {
            foreach ($meta as $key => $value) {
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
