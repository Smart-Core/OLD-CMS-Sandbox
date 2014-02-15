<?php

namespace SmartCore\Bundle\MediaBundle\Provider;

use SmartCore\Bundle\MediaBundle\Entity\Collection;
use SmartCore\Bundle\MediaBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ProviderInterface
{
    /**
     * Получить ссылку на файл.
     *
     * @param integer $id
     * @param string|null $filter
     * @return string|null
     */
    public function get($id, $filter = null);

    /**
     * @param UploadedFile $file
     * @param int $category
     * @param array $tags
     * @return int - ID файла в коллекции.
     */
    public function upload(UploadedFile $file, $category = null, array $tags = null);

    /**
     * @param int $id
     * @return bool
     */
    public function remove($id);

    /**
     * Получить список файлов.
     *
     * @param int|null $categoryId
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return File[]|null
     */
    public function findBy($categoryId = null, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param Collection $collection
     * @return $this
     */
    public function setCollection(Collection $collection);
}
