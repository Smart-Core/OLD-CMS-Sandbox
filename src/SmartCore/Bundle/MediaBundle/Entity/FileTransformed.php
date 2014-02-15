<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_files_transformed",
 *      indexes={
 *          @ORM\Index(name="filter", columns={"filter"})
 *      }
 * )
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
     * @ORM\JoinColumn(nullable=false)
     */
    protected $file;

    /**
     * @ORM\ManyToOne(targetEntity="Collection", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $collection;

    /**
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
     * @var integer
     *
     * @ORM\Column(type="bigint")
     */
    protected $size;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

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
