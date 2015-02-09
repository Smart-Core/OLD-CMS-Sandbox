<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;
use SmartCore\Bundle\CMSBundle\Model\SignedTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="engine_regions",
 *      indexes={
 *          @ORM\Index(columns={"position"}),
 *      }
 * )
 * @UniqueEntity(fields="name", message="Регион с таким именем уже используется")
 */
class Region
{
    use SignedTrait;
    use CreatedAtTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(min = "0", minMessage = "Минимальное значение 0.", max = "255", maxMessage = "Максимальное значение 255.")
     */
    protected $position;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $descr;

    /**
     * @var Folder[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Folder", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="engine_regions_inherit")
     */
    protected $folders;

    /**
     * @param string|null $name
     * @param string|null $descr
     */
    public function __construct($name = null, $descr = null)
    {
        $this->created_at   = new \DateTime();
        $this->folders      = new ArrayCollection();
        $this->descr        = $descr;
        $this->name         = $name;
        $this->position     = 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $descr = $this->getDescr();

        return empty($descr) ? $this->getName() : $descr.' ('.$this->getName().')';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $descr
     * @return $this
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * @param Folder $folder
     * @return $this
     */
    public function setFolder(Folder $folder)
    {
        $this->folders->add($folder);

        return $this;
    }

    /**
     * @param Folder[] $folder
     * @return $this
     */
    public function setFolders($folders)
    {
        $this->folders = $folders;

        return $this;
    }

    /**
     * @return Folder[]|ArrayCollection
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        if ('content' !== $this->name) {
            $this->name = $name;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $pos
     * @return $this
     */
    public function setPosition($pos)
    {
        if (empty($pos)) {
            $pos = 0;
        }

        $this->position = $pos;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
