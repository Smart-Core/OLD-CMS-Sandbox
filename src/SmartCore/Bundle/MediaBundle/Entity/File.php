<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="media_files")
 */
class File
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(name="collection_id", nullable=false)
     *
     * @var Collection
     */
    protected $collection;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id")
     *
     * @var Category
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(name="storage_id", nullable=false)
     *
     * @var Storage
     */
    protected $storage;

    /**
     * @ORM\Column(type="string", name="relative_path", length=100, nullable=true)
     */
    protected $relativePath;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $filename;

    /**
     * @ORM\Column(name="original_filename", type="string", length=255)
     */
    protected $originalFilename;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $type;

    /**
     * @ORM\Column(name="mime_type", type="string", length=32)
     */
    protected $mimeType;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->storage   = null;
    }

    /**
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        if (null === $this->storage) {
            $this->storage = $this->collection->getDefaultStorage();
        }
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Collection $collection
     * @return $this
     */
    public function setCollection(Collection $collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param Storage $storage
     * @return $this
     */
    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @return Storage
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $relativePath
     * @return $this
     */
    public function setRelativePath($relativePath)
    {
        $this->relativePath = $relativePath;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelativePath()
    {
        return $this->relativePath;
    }

    /**
     * @param string $originalFilename
     * @return $this
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->filename = hash('crc32', $originalFilename) . '.' . strtolower(substr($originalFilename, strrpos($originalFilename, '.') + 1));
        $this->originalFilename = $originalFilename;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $mimeType
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param integer $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }
}
