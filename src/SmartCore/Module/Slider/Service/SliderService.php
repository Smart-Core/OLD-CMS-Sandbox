<?php

namespace SmartCore\Module\Slider\Service;

use Doctrine\ORM\EntityManager;
use SmartCore\Module\Slider\Entity\Slide;
use SmartCore\Module\Slider\Entity\Slider;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SliderService
{
    use ContainerAwareTrait;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $origitalDir;

    /**
     * @var string
     */
    protected $webDir;

    /**
     * Constructor.
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function save(Slide $slide)
    {
        $file = $slide->getFile();

        $filename = md5(uniqid(mt_rand(), true) . $file->getFilename()).'.'.$file->guessExtension();
        $file->move($this->webDir, $filename);

        $slide
            ->setFileName($filename)
            ->setOriginalFileName($file->getClientOriginalName())
            ->setUserId($this->container->get('security.context')->getToken()->getUser()->getId())
        ;

        $this->em->persist($slide);
        $this->em->flush($slide);

        return $this;
    }

    /**
     * @return Slide[]
     */
    public function all()
    {
        return $this->em->getRepository('SliderModule:Slide')->findBy([], ['position' => 'ASC']);
    }

    /**
     * @return Slider[]
     */
    public function allSliders()
    {
        return $this->em->getRepository('SliderModule:Slider')->findBy([], ['title' => 'ASC']);
    }

    /**
     * @param Slider $slider
     * @return $this
     */
    public function createSlider(Slider $slider)
    {
        $this->em->persist($slider);
        $this->em->flush($slider);

        return $this;
    }

    /**
     * @param Slider $slider
     * @return $this
     */
    public function updateSlider(Slider $slider)
    {
        $this->em->persist($slider);
        $this->em->flush($slider);

        return $this;
    }

    /**
     * @param  Slider $slider
     * @return $this
     */
    public function deleteSlider(Slider $slider)
    {
        $this->em->remove($slider);
        $this->em->flush($slider);

        return $this;
    }

    /**
     * @param  int $id
     * @return Slider
     */
    public function getSlider($id)
    {
        return $this->em->getRepository('SliderModule:Slider')->find($id);
    }

    /**
     * @throws \RuntimeException
     */
    public function initImagesDirectory()
    {
        $webDir = '/images/slider'; // @todo настройку пути хранения слайдов, для разных "групп" свой путь.

        $dir = $this->container->getParameter('imagine.web_root') . $webDir;

        if (!is_dir($dir) and false === @mkdir($dir, 0777, true)) {
            throw new \RuntimeException(sprintf("Unable to create the %s directory (%s)\n", $webDir, $dir));
        }

        $this->webDir = $dir;
    }

    public function getWebPath()
    {
        return 'images/slider/';
    }
}
