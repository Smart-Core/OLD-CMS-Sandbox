<?php

namespace SmartCore\Bundle\MediaBundle\Provider;

use SmartCore\Bundle\MediaBundle\Entity\File;

interface ProviderInterface
{
    /**
     * Получить ссылку на файл.
     *
     * @param int $id
     * @param string|null $filter
     *
     * @return string|null
     */
    public function get($id, $filter = null);

    /**
     * @param File $file
     */
    public function upload(File $file);

    /**
     * @param int $id
     *
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
     *
     * @return File[]|null
     */
    public function findBy($categoryId = null, array $orderBy = null, $limit = null, $offset = null);
}
