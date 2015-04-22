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

        return $this->generatePattern($filename.'.'.$file->getUploadedFile()->getClientOriginalExtension());
    }

    /**
     * @param string|null $filter
     *
     * @return string
     */
    public function generateRelativePath(File $file, $filter = null)
    {
        $relativePath = $file->getStorage()->getRelativePath().$file->getCollection()->getRelativePath();

        if (!$filter) {
            $filter = $file->getCollection()->getDefaultFilter();
        }

        if (empty($filter)) {
            $filter = 'orig';
        }

        return $relativePath.'/'.$filter.$file->getRelativePath();
    }

    /**
     * @param string|null $pattern
     *
     * @return mixed|string
     */
    protected function generatePattern($pattern = null)
    {
        $pattern = str_replace('{year}',     date('Y'), $pattern);
        $pattern = str_replace('{month}',    date('m'), $pattern);
        $pattern = str_replace('{day}',      date('d'), $pattern);
        $pattern = str_replace('{hour}',     date('H'), $pattern);
        $pattern = str_replace('{minutes}',  date('i'), $pattern);
        $pattern = str_replace('{rand(10)}', substr(md5(microtime(true).uniqid()), 0, 10), $pattern);

        return $pattern;
    }

    /**
     * @param string $name
     *
     * @return string
     *
     * @todo транслитерацию.
     */
    public function translit($name)
    {
        $name = preg_replace('/[^\\pL\d.]+/u', '-', $name);

        $iso = [
            "Є" => "YE", "І" => "I", "Ѓ" => "G", "і" => "i", "№" => "N", "є" => "ye", "ѓ" => "g",
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D",
            "Е" => "E", "Ё" => "YO", "Ж" => "ZH",
            "З" => "Z", "И" => "I", "Й" => "J", "К" => "K", "Л" => "L",
            "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R",
            "С" => "S", "Т" => "T", "У" => "U", "Ф" => "F", "Х" => "H",
            "Ц" => "C", "Ч" => "CH", "Ш" => "SH", "Щ" => "SHH", "Ъ" => "'",
            "Ы" => "Y", "Ь" => "", "Э" => "E", "Ю" => "YU", "Я" => "YA",
            "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d",
            "е" => "e", "ё" => "yo", "ж" => "zh",
            "з" => "z", "и" => "i", "й" => "j", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shh", "ъ" => "",
            "ы" => "y", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", "«" => "", "»" => "", "—" => "-",
        ];

        $name = strtr($name, $iso);
        $name = trim($name, '-');

        // transliterate
        if (function_exists('iconv')) {
            $name = iconv('utf-8', 'us-ascii//TRANSLIT', $name);
        }

        return strtolower($name);
    }
}
