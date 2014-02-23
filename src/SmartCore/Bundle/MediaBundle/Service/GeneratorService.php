<?php

namespace SmartCore\Bundle\MediaBundle\Service;

use SmartCore\Bundle\MediaBundle\Entity\File;

class GeneratorService
{
    /**
     * @param File $file
     *
     * @return string
     */
    public function generateFilePath(File $file)
    {
        return $this->generatePattern($file->getCollection()->getFileRelativePathPattern());
    }

    /**
     * @param File $file
     *
     * @return string
     */
    public function generateFileName(File $file)
    {
        $filename = $file->getCollection()->getFilenamePattern();

        return $this->generatePattern($filename . '.' . $file->getUploadedFile()->getClientOriginalExtension());
    }

    /**
     * @param string|null $filter
     * @return string
     */
    public function generateRelativePath(File $file, $filter = null)
    {
        $relativePath = $file->getStorage()->getRelativePath() . $file->getCollection()->getRelativePath();

        if (!$filter) {
            $filter = $file->getCollection()->getDefaultFilter();
        }

        if (empty($filter)) {
            $filter = 'orig';
        }

        return $relativePath . '/' . $filter . $file->getRelativePath();
    }


    /**
     * @param string|null $pattern
     * @return mixed|string
     */
    protected function generatePattern($pattern = null)
    {
        $pattern = str_replace('{year}',     date('Y'), $pattern);
        $pattern = str_replace('{month}',    date('m'), $pattern);
        $pattern = str_replace('{day}',      date('d'), $pattern);
        $pattern = str_replace('{hour}',     date('H'), $pattern);
        $pattern = str_replace('{minutes}',  date('i'), $pattern);
        $pattern = str_replace('{rand(10)}', substr(md5(microtime(true) . uniqid()), 0, 10), $pattern);

        return $pattern;
    }
}
