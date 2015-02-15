<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_collections")
 */
class Collection
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $default_filter;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $params;

    /**
     * @var Storage
     *
     * @ORM\ManyToOne(targetEntity="Storage", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $default_storage;

    /**
     * Относительный путь можно менять, только если нету файлов в коллекции
     * либо предусмотреть как-то переименовывание пути в провайдере.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $relative_path;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $file_relative_path_pattern;

    /**
     * Маска имени файла. Если пустая строка, то использовать оригинальное имя файла,
     * совместимое с вебформатом т.е. без пробелов и русских букв.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $filename_pattern;

    /**
     * @var File[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="collection", fetch="EXTRA_LAZY")
     */
    protected $files;

    /**
     * @param string $relativePath
     */
    public function __construct($relativePath = '')
    {
        $this->created_at       = new \DateTime();
        $this->files            = new ArrayCollection();
        $this->relative_path    = $relativePath;

        $this->filename_pattern            = '{hour}_{minutes}_{rand(10)}';
        $this->file_relative_path_pattern  = '/{year}/{month}/{day}';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @param Storage $default_storage
     * @return $this
     */
    public function setDefaultStorage(Storage $default_storage)
    {
        $this->default_storage = $default_storage;

        return $this;
    }

    /**
     * @return Storage
     */
    public function getDefaultStorage()
    {
        return $this->default_storage;
    }

    /**
     * @param string $default_filter
     * @return $this
     */
    public function setDefaultFilter($default_filter)
    {
        $this->default_filter = $default_filter;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultFilter()
    {
        return $this->default_filter;
    }

    /**
     * @param array|null $params
     * @return $this
     */
    public function setParams(array $params = null)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $file_relative_path_pattern
     * @return $this
     */
    public function setFileRelativePathPattern($file_relative_path_pattern)
    {
        $this->file_relative_path_pattern = $file_relative_path_pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileRelativePathPattern()
    {
        return $this->file_relative_path_pattern;
    }

    /**
     * @param string $filename_pattern
     * @return $this
     */
    public function setFilenamePattern($filename_pattern)
    {
        $this->filename_pattern = $filename_pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilenamePattern()
    {
        return $this->filename_pattern;
    }

    /**
     * @param File[] $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
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
}
