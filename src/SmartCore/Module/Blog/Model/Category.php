<?php

namespace SmartCore\Module\Blog\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields={"slug", "parent"}, message="В каждой категории должен быть уникальный сегмент URI.")
 */
abstract class Category implements CategoryInterface
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\Title;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent")
     */
    protected $parent;

    /**
     * @var Category[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, unique=true)
     */
    protected $slug;

    /**
     * @var Article[]
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category", fetch="EXTRA_LAZY")
     */
    protected $articles;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->articles   = new ArrayCollection();
        $this->children   = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return Category[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Article[]
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param CategoryInterface $parent
     * @return $this
     */
    public function setParent(CategoryInterface $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return Category[]|array
     */
    public function getParents()
    {
        $parents = new ArrayCollection();

        if ($this->getParent()) {
            $parents->add($this->getParent());
            $this->buildParents($parents);
        }

        return $parents->toArray();
    }

    /**
     * Рекурсивный обход для построение списка всех родительских категорий.
     *
     * @param ArrayCollection $parents
     * @return void
     */
    protected function buildParents(ArrayCollection $parents)
    {
        $category = $parents->last();

        if ($category->getParent()) {
            $parents->add($category->getParent());
            $this->buildParents($parents);
        }
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Получить полный путь, включая родительские категории.
     *
     * @return string
     */
    public function getSlugFull()
    {
        $slug = $this->getSlug();

        if ($this->getParent()) {
            $slug  = $this->getParent()->getSlugFull().'/'.$slug;
        }

        return $slug;
    }
}
