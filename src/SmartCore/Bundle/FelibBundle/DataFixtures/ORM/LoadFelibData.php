<?php

namespace SmartCore\Bundle\FelibBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SmartCore\Bundle\FelibBundle\Entity\Library;
use SmartCore\Bundle\FelibBundle\Entity\LibraryPath;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadFelibData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // jquery
        $lib = new Library();
        $lib->setName('jquery')
            ->setProirity(1000)
            ->setCurrentVersion('1.9.1')
            ->setFiles('jquery.min.js');
        $manager->persist($lib);
        $manager->flush($lib);

        $libPath = new LibraryPath();
        $libPath
            ->setLibId($lib->getId())
            ->setVersion('1.9.1')
            ->setPath('jquery/1.9.1/');
        $manager->persist($libPath);
        $manager->flush($libPath);

        // bootstrap
        $lib = new Library();
        $lib->setName('bootstrap')
            ->setRelatedBy('jquery')
            ->setCurrentVersion('2.3.2')
            ->setFiles('css/bootstrap.min.css,css/bootstrap-responsive.min.css,js/bootstrap.min.js');
        $manager->persist($lib);
        $manager->flush($lib);

        $libPath = new LibraryPath();
        $libPath
            ->setLibId($lib->getId())
            ->setVersion('2.3.2')
            ->setPath('bootstrap/2.3.2/');
        $manager->persist($libPath);
        $manager->flush($libPath);

        // jquery.cookie
        $lib = new Library();
        $lib->setName('jquery-cookie')
            ->setRelatedBy('jquery')
            ->setCurrentVersion('1.3.1')
            ->setFiles('jquery.cookie.js');
        $manager->persist($lib);
        $manager->flush($lib);

        $libPath = new LibraryPath();
        $libPath
            ->setLibId($lib->getId())
            ->setVersion('1.3.1')
            ->setPath('jquery-cookie/1.3.1/');
        $manager->persist($libPath);
        $manager->flush($libPath);

        // less
        $lib = new Library();
        $lib->setName('less')
            ->setCurrentVersion('1.7.0')
            ->setFiles('less.min.js');
        $manager->persist($lib);
        $manager->flush($lib);

        $libPath = new LibraryPath();
        $libPath
            ->setLibId($lib->getId())
            ->setVersion('1.7.0')
            ->setPath('less/1.7.0/');
        $manager->persist($libPath);
        $manager->flush($libPath);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
