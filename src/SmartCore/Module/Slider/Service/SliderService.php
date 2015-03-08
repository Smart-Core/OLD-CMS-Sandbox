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

    /**
     * @param  Slide $slide
     * @return $this
     */
    public function save(Slide $slide)
    {
        $file = $slide->getFile();

        $filename = md5(uniqid(mt_rand(), true).$file->getFilename()).'.'.$file->guessExtension();
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
     * @param Slide $slide
     * @return $this
     */
    public function updateSlide(Slide $slide)
    {
        $this->em->persist($slide);
        $this->em->flush($slide);

        return $this;
    }

    /**
     * @param Slide $slide
     * @return $this
     */
    public function deleteSlide(Slide $slide)
    {
        unlink($this->webDir.'/'.$slide->getFileName());

        $this->em->remove($slide);
        $this->em->flush($slide);

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
     * @return Slide
     */
    public function getSlide($id)
    {
        return $this->em->getRepository('SliderModule:Slide')->find($id);
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

        $dir = $this->container->getParameter('kernel.root_dir').'/../web'.$webDir;

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
