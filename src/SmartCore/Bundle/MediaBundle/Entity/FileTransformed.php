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
     */
    protected $original_file;

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="files")
     */
    protected $collection;

    /**
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="files")
     */
    protected $storage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

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
        $this->created_at = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

}
