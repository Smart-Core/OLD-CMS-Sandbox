<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\CMSBundle\Model\CreatedAtTrait;
use SmartCore\Bundle\CMSBundle\Model\SignedTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use SmartCore\Bundle\CMSBundle\Container;

/**
 * @ORM\Entity
 * @ORM\Table(name="engine_blocks",
 *      indexes={
 *          @ORM\Index(name="position", columns={"position"}),
 *      }
 * )
 * @UniqueEntity(fields="name", message="Блок с таким именем уже используется")
 */
class Block
{
    use SignedTrait;
    use CreatedAtTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $block_id;

    /**
     * @ORM\Column(type="smallint", nullable=TRUE)
     * @Assert\Range(min = "0", minMessage = "Минимальное значение 0.", max = "255", maxMessage = "Максимальное значение 255.")
     *
     * -Assert\Type(type="integer", message="bad :(")
     * -Assert\Regex(pattern="/\d+/", match=FALSE, message="BAD!" )
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=50, nullable=FALSE, unique=TRUE)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $descr;

    /**
     * @var Folder[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Folder", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="engine_blocks_inherit",
     *      joinColumns={@ORM\JoinColumn(name="block_id", referencedColumnName="block_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="folder_id", referencedColumnName="folder_id")}
     *      )
     */
    protected $folders;

    /**
     * Constructor.
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

        return (empty($descr)) ? $this->getName() : $descr . ' (' . $this->getName() . ')';
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->block_id;
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
     * @return Folder[]
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
     * @param integer $pos
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
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Получить кол-во включенных нод.
     *
     * @todo убрать в сервис.
     */
    public function getNodesCount()
    {
        $query = Container::get('doctrine.orm.entity_manager')->createQuery("
            SELECT COUNT(n.node_id)
            FROM CMSBundle:Node n
            JOIN CMSBundle:Block b
            WHERE b.block_id = {$this->block_id}
            AND n.block = b
        ");

        return $query->getSingleScalarResult();
    }
}
