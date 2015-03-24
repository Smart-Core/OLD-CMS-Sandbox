<?php

namespace SmartCore\Module\WebForm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\WebForm\Entity\MessageRepository")
 * @ORM\Table(name="webforms_messages")
 */
class Message
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UserId;

    const STATUS_NEW         = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_FINISHED    = 2;
    const STATUS_REJECTED    = 3;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $data;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default":0})
     */
    protected $status;

    /**
     * @var WebForm
     *
     * @ORM\ManyToOne(targetEntity="WebForm", inversedBy="fields")
     */
    protected $web_form;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->status     = self::STATUS_NEW;
    }

    /**
     * @return string
     */
    public function getBriefly()
    {
        $str = '';

        foreach ($this->getData() as $data) {
            $str .= $data.', ';
        }

        $a = strip_tags($str);

        $dotted = (mb_strlen($a, 'utf-8') > 80) ? '...' : '';

        return mb_substr($a, 0, 80, 'utf-8').$dotted;
    }

    /**
     * @return array
     */
    static public function getFormChoicesStatuses()
    {
        return [
            self::STATUS_NEW            => 'Новый',
            self::STATUS_IN_PROGRESS    => 'В работе',
            self::STATUS_FINISHED       => 'Выпролнен',
            self::STATUS_REJECTED       => 'Оклонён',
        ];
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getDataValue($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return WebForm
     */
    public function getWebForm()
    {
        return $this->web_form;
    }

    /**
     * @param WebForm $web_form
     * @return $this
     */
    public function setWebForm($web_form)
    {
        $this->web_form = $web_form;

        return $this;
    }
}
