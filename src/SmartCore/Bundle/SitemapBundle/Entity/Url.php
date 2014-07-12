<?php

namespace SmartCore\Bundle\SitemapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="sitemap_urls",
 *      indexes={
 *          @ORM\Index(name="title_hash", columns={"title_hash"})
 *      }
 * )
 * @UniqueEntity(fields={"loc"}, message="Ссылка должна быть уникальной.")
 */
class Url
{
    /**
     * Change Frequency Type Constants.
     */
    const ALWAYS  = 'always';
    const HOURLY  = 'hourly';
    const DAILY   = 'daily';
    const WEEKLY  = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY  = 'yearly';
    const NEVER   = 'never';

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
    protected $is_visited;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $loc;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $referer;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $title_hash;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastmod;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $changefreq;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $priority;

    /**
     * @var array
     */
    protected $images;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $status;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->changefreq   = null;
        $this->is_visited   = false;
        $this->lastmod      = null;
        $this->priority     = null;
        $this->referer      = null;
        $this->status       = 200;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $loc
     * @return $this
     */
    public function setLoc($loc)
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * @return string
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * @param float $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return float
     */
    public function getPriority()
    {
        if ($this->priority == 1) {
            return '1.0';
        }

        return $this->priority;
    }

    /**
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param string $changefreq
     * @return $this
     */
    public function setChangefreq($changefreq)
    {
        $this->changefreq = $changefreq;

        return $this;
    }

    /**
     * @return string
     */
    public function getChangefreq()
    {
        return $this->changefreq;
    }

    /**
     * @param bool $is_visited
     * @return $this
     */
    public function setIsVisited($is_visited)
    {
        $this->is_visited = $is_visited;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsVisited()
    {
        return $this->is_visited;
    }

    /**
     * @param \DateTime $lastmod
     * @return $this
     */
    public function setLastmod($lastmod)
    {
        $this->lastmod = $lastmod;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->title_hash = md5($title);

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
     * @return string
     */
    public function getTitleHash()
    {
        return $this->title_hash;
    }
}
