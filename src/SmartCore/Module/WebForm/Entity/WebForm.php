<?php

namespace SmartCore\Module\WebForm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $send_button_title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $send_notice_emails;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email()
     */
    protected $from_email;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $final_text;

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

    /**
     * @return string
     */
    public function getSendButtonTitle()
    {
        return $this->send_button_title;
    }

    /**
     * @param string $send_button_title
     * @return $this
     */
    public function setSendButtonTitle($send_button_title)
    {
        $this->send_button_title = $send_button_title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSendNoticeEmails()
    {
        return $this->send_notice_emails;
    }

    /**
     * @param string $send_notice_emails
     * @return $this
     */
    public function setSendNoticeEmails($send_notice_emails)
    {
        $this->send_notice_emails = $send_notice_emails;

        return $this;
    }

    /**
     * @return string
     */
    public function getFinalText()
    {
        return $this->final_text;
    }

    /**
     * @param string $final_text
     * @return $this
     */
    public function setFinalText($final_text)
    {
        $this->final_text = $final_text;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->from_email;
    }

    /**
     * @param string $from_email
     * @return $this
     */
    public function setFromEmail($from_email)
    {
        $this->from_email = $from_email;

        return $this;
    }
}
