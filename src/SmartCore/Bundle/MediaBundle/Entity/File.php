<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_files",
 *      indexes={
 *          @ORM\Index(columns={"type"})
 *      }
 * )
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_preuploaded;

    /**
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $collection;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="files")
     */
    protected $category;

    /**
     * @var Storage
     *
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $storage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $relative_path;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $filename;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $original_filename;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=8)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $mime_type;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $original_size;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var FileTransformed[]
     *
     * @ORM\OneToMany(targetEntity="FileTransformed", mappedBy="file", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $filesTransformed;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * Constructor.
     */
    public function __construct(UploadedFile $uploadedFile = null)
    {
        $this->created_at     = new \DateTime();
        $this->is_preuploaded = true;
        $this->user_id        = 0;
        $this->storage        = null;

        if ($uploadedFile) {
            $this->uploadedFile = $uploadedFile;

            // @todo video и т.д
            if (false !== strpos($uploadedFile->getMimeType(), 'image/')) {
                $this->setType('image');
            } else {
                $this->setType($uploadedFile->getType());
            }

            $this->setMimeType($uploadedFile->getMimeType());
            $this->setOriginalFilename($uploadedFile->getClientOriginalName());
            $this->setOriginalSize($uploadedFile->getSize());
            $this->setSize($uploadedFile->getSize());
        }
    }

    /**
     * @return int
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
        return $this->created_at;
    }

    /**
     * @param string|null $filter
     * @return string
     */
    public function getFullRelativePath($filter = null)
    {
        $relativePath = $this->getStorage()->getRelativePath().$this->getCollection()->getRelativePath();

        if (empty($filter)) {
            $filter = 'orig';
        }

        return $relativePath.'/'.$filter.$this->relative_path;
    }

    /**
     * @param string|null $filter
     * @return string
     */
    public function getFullRelativeUrl($filter = null)
    {
        return $this->getFullRelativePath($filter).'/'.$this->getFilename();
    }

    /**
     * @param bool $is_preuploaded
     * @return $this
     */
    public function setIsPreuploaded($is_preuploaded)
    {
        $this->is_preuploaded = $is_preuploaded;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPreuploaded()
    {
        return $this->is_preuploaded;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
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
        $this->storage = $collection->getDefaultStorage();

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
     * @return FileTransformed[]|
     */
    public function getFilesTransformed()
    {
        return $this->filesTransformed;
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
     * @param string $relative_path
     * @return $this
     */
    public function setRelativePath($relative_path)
    {
        $this->relative_path = $relative_path;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relative_path;
    }

    /**
     * @param string $originalFilename
     * @return $this
     */
    public function setOriginalFilename($originalFile)
    {
        $this->original_filename = $originalFile;

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
     * @param int $original_size
     * @return $this
     */
    public function setOriginalSize($original_size)
    {
        $this->original_size = $original_size;

        return $this;
    }

    /**
     * @return int
     */
    public function getOriginalSize()
    {
        return $this->original_size;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isMimeType($type)
    {
        if (strpos($this->getMimeType(), $type) !== false) {
            return true;
        }

        return false;
    }
}
