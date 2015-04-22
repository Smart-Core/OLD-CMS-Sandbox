<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_files_transformed",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"filter", "file_id"}),
 *      }
 * )
 */
class FileTransformed
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="File", inversedBy="filesTransformed", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $file;

    /**
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $collection;

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
     * @ORM\Column(type="string", length=16)
     */
    protected $filter;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return string
     */
    public function getFullRelativeUrl()
    {
        return $this->getFile()->getFullRelativeUrl($this->getFilter());
    }

    /**
     * @param Collection $collection
     *
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
     * @param File $file
     *
     * @return $this
     */
    public function setFile(File $file)
    {
        $this->file = $file;
        $this->setCollection($file->getCollection());
        $this->setStorage($file->getStorage());

        return $this;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $filter
     *
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param int $size
     *
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
     * @param Storage $storage
     *
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
}
