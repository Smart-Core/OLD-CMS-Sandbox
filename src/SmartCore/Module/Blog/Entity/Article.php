<?php

namespace SmartCore\Module\Blog\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Blog\Model\Article as SmartArticle;
use SmartCore\Module\Blog\Model\CategoryTrait;
use SmartCore\Module\Blog\Model\ImagedArticleInterface;
use SmartCore\Module\Blog\Model\SignedArticleInterface;
use SmartCore\Module\Blog\Model\TagTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Blog\Repository\ArticleRepository")
 * @ORM\Table(name="blog_articles",
 *      indexes={
 *          @ORM\Index(columns={"created_at"})
 *      }
 * )
 */
class Article extends SmartArticle implements SignedArticleInterface, ImagedArticleInterface
{
    use CategoryTrait;
    use TagTrait;

    /**
     * @ORM\ManyToOne(targetEntity="SmartCore\Bundle\FOSUserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id")
     */
    protected $author;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $image_id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $is_commentable;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     *
     * @var UploadedFile
     */
    protected $image;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->image = null;
        $this->is_commentable = true;
    }

    /**
     * @param UserInterface $author
     * @return $this
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param UploadedFile $image
     * @return $this
     */
    public function setImage(UploadedFile $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return UploadedFile|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param int $image_id
     * @return $this
     */
    public function setImageId($image_id)
    {
        $this->image_id = $image_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageId()
    {
        return $this->image_id;
    }

    /**
     * @param bool $is_commentable
     * @return $this
     */
    public function setIsCommentable($is_commentable)
    {
        $this->is_commentable = $is_commentable;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsCommentable()
    {
        return $this->is_commentable;
    }

    /**
     * @return bool
     */
    public function isCommentable()
    {
        return $this->is_commentable;
    }
}
