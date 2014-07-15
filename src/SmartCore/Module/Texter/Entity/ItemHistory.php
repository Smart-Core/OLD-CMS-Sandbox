<?php

namespace SmartCore\Module\Texter\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="texter_history",
 *      indexes={
 *          @ORM\Index(name="item_id", columns={"item_id"}),
 *          @ORM\Index(name="is_deleted", columns={"is_deleted"}),
 *      }
 * )
 */
class ItemHistory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_deleted = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $item_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=8)
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
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $meta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $user_id = 0;

    /**
     * Constructor.
     */
    public function __construct(Item $item = null)
    {
        if ($item) {
            $this->editor   = $item->getEditor();
            $this->item_id  = $item->getId();
            $this->locale   = $item->getLocale();
            $this->meta     = $item->getMeta();
            $this->text     = $item->getText();
            $this->user_id  = $item->getUserId();
        }

        $this->created  = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Получить анонс.
     *
     * @return string
     */
    public function getAnnounce()
    {
        $a = strip_tags($this->text);

        $dotted = (mb_strlen($a, 'utf-8') > 120) ? '...' : '';

        return mb_substr($a, 0, 120, 'utf-8') . $dotted;
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
     * @param bool $is_deleted
     * @return $this
     */
    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * @param int $item_id
     * @return $this
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
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
     * @param array $meta
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
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
     * @return string
     */
    public function getText()
    {
        return $this->text;
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
