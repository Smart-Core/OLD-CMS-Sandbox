<?php

namespace SmartCore\Bundle\MediaBundle\Twig;

use SmartCore\Bundle\CMSBundle\Tools\Breadcrumbs;
use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaExtension extends \Twig_Extension
{
    /**
     * @var MediaCloudService
     */
    protected $media;

    /**
     * Constructor.
     */
    public function __construct($media)
    {
        $this->media = $media;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            'smart_media'     => new \Twig_Function_Method($this, 'generateFileUrl'),
            'smart_media_img' => new \Twig_Function_Method($this, 'renderImgTag'),
        ];
    }

    /**
     * @param  int $fileId
     * @param  int $collectionId
     * @return string
     */
    public function generateFileUrl($fileId, $collectionId = 1)
    {
        $mc = $this->media->getCollection($collectionId);

        return (empty($fileId)) ? null :  $mc->get($fileId);
    }

    public function renderImgTag($fileId, $collectionId = 1)
    {
        return (empty($fileId)) ? null : '<img src="' . $this->generateFileUrl($fileId, $collectionId)  . '" alt="" />';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smart_media_twig_extension';
    }
}
