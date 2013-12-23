<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use SmartCore\Bundle\CMSBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Entity\Node;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class HelloController extends Controller
{    
    public function indexAction()
    {
        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $this->get('html')
            ->doctype('xhtml')
            ->lang('en')
            ->title('hi :)')
        ;

        // ===========================================================================================================
        // Эксперименты с инсталляцией модуля.
        // ===========================================================================================================

        $fs = new Filesystem();
        $cacheDir = $this->get('kernel')->getCacheDir();
        $rootDir = $this->get('kernel')->getRootDir();
//        $fs->mkdir($cacheDir . 'test1');
//        $fs->remove($cacheDir . 'EzPublishLegacyBundle');


        // 1) Распаковка архива.

        //$phar = new \PharData($rootDir . '/../test_modules_install/SmartCore.tar', null, null, \Phar::TAR);
        //$phar->extractTo($rootDir . '/../src', null, true);

        $zip = new \ZipArchive();
        $zip->open($rootDir . '/../dist/SmartCore.zip');
        $zip->extractTo($rootDir . '/../src');

        // 2) Подключение модуля.

        /*
        $modulesList = $this->get('kernel')->getModules();
        $modulesList['Example'] = '\SmartCore\Module\Example\ExampleModule';
        ksort($modulesList);

        $modulesIni = '';
        foreach ($modulesList as $key => $value) {
            $modulesIni .= "$key = $value\n";
        }

        file_put_contents($rootDir.'/usr/modules.ini', $modulesIni);


        // 3) Очистка кэша. @todo

        // 4) Установка ресурсов (Resources/public). @todo

        // 5) Обновление БД.

        /*
        $application = new Application($this->get('kernel'));
        $application->setAutoExit(false);
        $input = new ArrayInput(['command' => 'doctrine:schema:update', "--force" => true]);
        $output = new BufferedOutput();

        $retval = $application->run($input, $output);

        ld($output->fetch());
        */


        // ===========================================================================================================
        // End/ Эксперименты с инсталляцией модуля.
        // ===========================================================================================================


//        ld($this->renderView("Menu::menu.html.twig");
//        ld($this->forward('Texter:Test:hello', ['text' => 'yahoo :)'])->getContent());
//        ld($this->forward('2:Test:index')->getContent());

//        $tmp = $this->forward(8);
//        $tmp = $this->forward('MenuModule:Menu:index');
//        ld(get_class($tmp));
//        ld($tmp->getContentRaw());
//        echo $tmp->getContent();

        /*
        $activeTheme = $this->get('liip_theme.active_theme');
        $activeTheme->setThemes(['web', 'tablet', 'phone']);
        $activeTheme->setName('phone');
        */

//        $nodes = $em->getRepository('CMSBundle:Node')->findInInheritanceFolder($folder_id);

//        $b = $em->getRepository('CMSBundle:Block')->find(2);
//        $folder = $em->find('CMSBundle:Folder', 1);
//        $b->setFolders($folder);
//        ld($b->getFolders());

//        $em->persist($folder);
//        $em->flush();

//        $repo = $em->getRepository('CMSBundle:Node')->findIn([4, 6, 5, 1]);
//        $node = $em->find('CMSBundle:Node', 8);
//        ld($repo);

        /** @var $node Node */
        //ld($this->get('cms.folder')->getUri($node->getFolder()->getId()));

        return $this->render('HtmlBundle::test.html.twig', ['hello' => 'Hello World!']);
    }
}
