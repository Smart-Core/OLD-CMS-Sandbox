<?php

namespace SmartCore\Module\WebForm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="webforms")
 */
class WebForm
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\NameUnique;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\UserId;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default":0})
     */
    protected $is_use_captcha;

    /**
     * @var WebFormField[]
     *
     * @ORM\OneToMany(targetEntity="WebFormField", mappedBy="web_form")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $fields;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="web_form")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $messages;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->fields       = new ArrayCollection();
        $this->messages     = new ArrayCollection();
    }

    /**
     * @see getName
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * @return WebFormField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param WebFormField[] $fields
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message[] $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsUseCaptcha()
    {
        return $this->is_use_captcha;
    }

    /**
     * @param boolean $is_use_captcha
     * @return $this
     */
    public function setIsUseCaptcha($is_use_captcha)
    {
        $this->is_use_captcha = $is_use_captcha;

        return $this;
    }
}
