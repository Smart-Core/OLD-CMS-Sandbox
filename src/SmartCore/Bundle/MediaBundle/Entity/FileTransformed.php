<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_files_transformed")
 */
class FileTransformed
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="File")
     * @ORM\JoinColumn(name="original_file_id")
     */
    protected $originalFile;

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="files")
     * @ORM\JoinColumn(name="collection_id")
     */
    protected $collection;

    /**
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="files")
     * @ORM\JoinColumn(name="storage_id")
     */
    protected $storage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(type="bigint")
     */
    protected $size;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
}
