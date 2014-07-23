<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;
use SmartCore\Bundle\CMSBundle\Model\SignedTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="engine_appearance_history",
 *      indexes={
 *          @ORM\Index(name="hash_appearance_history", columns={"hash"}),
 *          @ORM\Index(name="path_appearance_history", columns={"path"}),
 *          @ORM\Index(name="filename_appearance_history", columns={"filename"}),
 *      }
 * )
 */
class AppearanceHistory
{
    use CreatedAtTrait;
    use SignedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $filename;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $hash;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->hash = md5($code);

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}
