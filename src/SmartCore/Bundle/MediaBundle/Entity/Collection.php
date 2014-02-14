<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\MediaBundle\Entity\Storage;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_collections")
 */
class Collection
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128)
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $params;

    /**
     * @ORM\ManyToOne(targetEntity="Storage", cascade={"persist"})
     * @ORM\JoinColumn(name="default_storage_id", nullable=false)
     *
     * @var Storage
     */
    protected $defaultStorage;

    /**
     * Относительный путь можно менять, только если нету файлов в коллекции
     * либо предусмотреть как-то переименовывание пути в провайдере.
     *
     * @var string
     *
     * @ORM\Column(name="relative_path", type="string", length=255)
     */
    protected $relativePath;

    /**
     * Маска имени файла. Если пустая строка, то использовать оригинальное имя файла,
     * совместимое с вебформатом т.е. без пробелов и русских букв.
     *
     * @ORM\Column(name="filename_pattern", type="string", length=64)
     *
     * @var string
     */
    protected $filenamePattern;

    /**
     * @ORM\Column(name="file_relative_path_pattern", type="string", length=255)
     *
     * @var string
     */
    protected $fileRelativePathPattern;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="collection")
     *
     * @var File[]|ArrayCollection
     */
    protected $files;

    /**
     * Constructor.
     *
     * @param string $relativePath
     */
    public function __construct($relativePath = '')
    {
        $this->createdAt        = new \DateTime();
        $this->files            = new ArrayCollection();
        $this->relativePath     = $relativePath;

        $this->filenamePattern          = '{hour}_{minutes}_{rand(10)}';
        $this->fileRelativePathPattern  = '{year}/{month}/{day}/';
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param Storage $defaultStorage
     * @return $this
     */
    public function setDefaultStorage(Storage $defaultStorage)
    {
        $this->defaultStorage = $defaultStorage;

        return $this;
    }

    /**
     * @return Storage
     */
    public function getDefaultStorage()
    {
        return $this->defaultStorage;
    }

    /**
     * @param string $filenamePattern
     * @return $this
     */
    public function setFilenamePattern($filenamePattern)
    {
        $this->filenamePattern = $filenamePattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilenamePattern()
    {
        return $this->filenamePattern;
    }

    /**
     * @param string $fileRelativePathPattern
     * @return $this
     */
    public function setFileRelativePathPattern($fileRelativePathPattern)
    {
        $this->fileRelativePathPattern = $fileRelativePathPattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileRelativePathPattern()
    {
        return $this->fileRelativePathPattern;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
    
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $relativePath
     * @return $this
     */
    public function setRelativePath($relativePath)
    {
        $this->relativePath = $relativePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }
}
