<?php

namespace SmartCore\Module\Blog\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImagedArticleInterface
{
    /**
     * @param UploadedFile $image
     * @return $this
     */
    public function setImage(UploadedFile $image);

    /**
     * @return UploadedFile
     */
    public function getImage();

    /**
     * @param integer $image_id
     * @return $this
     */
    public function setImageId($image_id);

    /**
     * @return integer
     */
    public function getImageId();
}
