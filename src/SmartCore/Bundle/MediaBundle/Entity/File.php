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
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $collection;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="files", cascade={"persist"})
     */
    protected $category;

    /**
     * @var Storage
     *
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $storage;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $relative_path;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $filename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $original_filename;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $mime_type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
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
     * @param mixed $relative_path
     * @return $this
     */
    public function setRelativePath($relative_path)
    {
        $this->relative_path = $relative_path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelativePath()
    {
        return $this->relative_path;
    }

    /**
     * @param string $originalFilename
     * @return $this
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->filename = hash('crc32', $originalFilename) . '.' . strtolower(substr($originalFilename, strrpos($originalFilename, '.') + 1));
        $this->original_filename = $originalFilename;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->original_filename;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
        $this->mime_type = $mimeType;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
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
