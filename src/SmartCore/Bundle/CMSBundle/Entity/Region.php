<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
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
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Description;
    use ColumnTrait\Position;
    use ColumnTrait\UserId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;
    /**
     * @var Folder[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Folder", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="engine_regions_inherit")
     */
    protected $folders;

    /**
     * @param string|null $name
     * @param string|null $description
     */
    public function __construct($name = null, $description = null)
    {
        $this->created_at   = new \DateTime();
        $this->folders      = new ArrayCollection();
        $this->description  = $description;
        $this->name         = $name;
        $this->position     = 0;
        $this->user_id      = 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $descr = $this->getDescription();

        return empty($descr) ? $this->getName() : $descr.' ('.$this->getName().')';
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
}
