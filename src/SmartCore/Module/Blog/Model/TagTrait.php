<?php

namespace SmartCore\Module\Blog\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @todo разобраться с инкрементами, притом надо учесть, что если статья удаляется или деактивируется, тогда декрементить все счетчики тэгов.
 */
trait TagTrait
{
    /**
     * @var TagInterface[]|ArrayCollection|null
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinTable(name="blog_articles_tags_relations",
     *      joinColumns={@ORM\JoinColumn(name="article_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id")}
     * )
     */
    protected $tags;

    /**
     * @param TagInterface $tag
     * @return $this
     */
    public function addTag(TagInterface $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            //$tag->increment();
        }

        return $this;
    }

    /**
     * @param TagInterface $tag
     * @return $this
     */
    public function removeTag(TagInterface $tag)
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            //$tag->decrement();
        }

        return $this;
    }

    /**
     * @param TagInterface[]|ArrayCollection $tags
     * @return $this
     */
    public function setTags($tags)
    {
        /*
        foreach ($this->tags as $tag) {
            $tag->decrement();
        }

        foreach ($tags as $tagNew) {
            $tagNew->increment();
        }
        */

        $this->tags = $tags;

        return $this;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
