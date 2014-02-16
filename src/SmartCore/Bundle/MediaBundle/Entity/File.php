<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_files",
 *      indexes={
 *          @ORM\Index(name="type", columns={"type"})
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
     * @ORM\Column(type="string", length=8)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $mime_type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $original_size;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
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
     * @ORM\OneToMany(targetEntity="FileTransformed", mappedBy="file", cascade={"persist"})
     */
    protected $filesTransformed;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->user_id    = 0;
        $this->storage    = null;
    }

    /**
     * @return string
     */
    public function generateRelativePath($filter = null)
    {
        // @todo проверка на установленный Storage и Collection

        $relativePath = $this->getStorage()->getRelativePath() . $this->getCollection()->getRelativePath();

        if (!$filter) {
            $filter = $this->getCollection()->getDefaultFilter();
        }

        return $relativePath . $this->generatePattern($filter);
    }

    /**
     * @param string|null $filter
     * @return mixed|string
     */
    public function generatePattern($filter = null)
    {
        $pattern = $this->getCollection()->getFileRelativePathPattern();
        $pattern = str_replace('{filter}', empty($filter) ? 'orig' : $filter, $pattern);
        $pattern = str_replace('{year}',  date('Y'), $pattern);
        $pattern = str_replace('{month}', date('m'), $pattern);
        $pattern = str_replace('{day}',   date('d'), $pattern);

        return $pattern;
    }

    /**
     * @return string
     */
    public function getFullRelativePath($filter = null)
    {
        if (empty($this->relative_path)) {
            $this->relative_path = $this->generatePattern();
        }

        return $this->generateRelativePath($filter);
    }

    /**
     * @return string
     */
    public function getFullRelativeUrl($filter = null)
    {
        return $this->getFullRelativePath($filter) . '/' . $this->getFilename();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param UploadedFile $file
     * @return $this
     */
    public function setFile(UploadedFile $file)
    {
        $this->uploadedFile = $file;
        $this->setFilename($file);

        // @todo video и т.д
        if (false !== strpos($file->getMimeType(), 'image/')) {
            $this->setType('image');
        } else {
            $this->setType($file->getType());
        }

        $this->setMimeType($file->getMimeType());
        $this->setOriginalSize($file->getSize());
        $this->setSize($file->getSize());

        return $this;
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
     * @return \SmartCore\Bundle\MediaBundle\Entity\FileTransformed[]
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
    public function setFilename($file)
    {
        $this->setOriginalFilename($file);

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
    public function setOriginalFilename(UploadedFile $originalFile)
    {
        $filename = $this->collection->getFilenamePattern();

        // @todo внешний генератор имён.
        $filename = str_replace('{hour}', date('H'), $filename);
        $filename = str_replace('{minutes}', date('i'), $filename);
        $filename = str_replace('{rand(10)}', substr(md5(microtime(true) . $originalFile->getClientOriginalName()), 0, 10), $filename);

        $this->filename = $filename . '.' . $originalFile->guessClientExtension();
        $this->original_filename = $originalFile->getClientOriginalName();

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

    /**
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
