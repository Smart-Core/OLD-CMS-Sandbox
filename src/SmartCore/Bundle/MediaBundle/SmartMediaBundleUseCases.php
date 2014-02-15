<?php

namespace SmartCore\Bundle\MediaBundle;

use SmartCore\Bundle\MediaBundle\Service\CollectionService;
use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SmartMediaBundleUseCases
{
    public function examples()
    {
        $container = 'container';

        $collection = new CollectionService($container);
        $cloud = new MediaCloudService($container);

        $file = new UploadedFile('/path/to/file', 'filename');

        // Создание файла в коллекции.
        $fileId = $collection->upload($file);

        // Создание файла в коллекции помеченные тэгами.
        $fileId = $collection->upload($file, ['tags' => ['user_id_123', 'symfony2']]);

        // Получить ссылку на оригинальный файл.
        $url = $collection->get($fileId);

        // Получить ссылку на трансформированный файл.
        // Примерно как тут http://cloudinary.com/documentation/image_transformations
        $url = $collection->get($fileId, [
            'width'  => 100,
            'height' => 150,
            'crop'   => 'fill',
        ]);
    }
}
