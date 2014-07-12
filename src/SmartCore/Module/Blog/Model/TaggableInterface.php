<?php

namespace SmartCore\Module\Blog\Model;

use Doctrine\Common\Collections\ArrayCollection;

interface TaggableInterface
{
    /**
     * @param TagInterface $tag
     * @return $this
     */
    public function addTag(TagInterface $tag);

    /**
     * @param TagInterface $tag
     * @return $this
     */
    public function removeTag(TagInterface $tag);

    /**
     * @param TagInterface[]|ArrayCollection $tags
     * @return $this
     */
    public function setTags($tags);

    /**
     * @return TagInterface[]|ArrayCollection
     */
    public function getTags();
}
