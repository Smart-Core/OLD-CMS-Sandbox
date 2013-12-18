<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
//use Symfony\Component\HttpFoundation\Response;
use SmartCore\Bundle\EngineBundle\Controller\Controller;
use SmartCore\Bundle\EngineBundle\Entity\Node;

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

//        ld($this->View->blocks);
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

//        $nodes = $em->getRepository('SmartCoreEngineBundle:Node')->findInInheritanceFolder($folder_id);

//        $b = $em->getRepository('SmartCoreEngineBundle:Block')->find(2);
//        $folder = $em->find('SmartCoreEngineBundle:Folder', 1);
//        $b->setFolders($folder);
//        ld($b->getFolders());

//        $em->persist($folder);
//        $em->flush();

//        $repo = $em->getRepository('SmartCoreEngineBundle:Node')->findIn([4, 6, 5, 1]);
//        $node = $em->find('SmartCoreEngineBundle:Node', 8);
//        ld($repo);

        /** @var $node Node */
        //ld($this->get('engine.folder')->getUri($node->getFolder()->getId()));

        return $this->render('HtmlBundle::test.html.twig', ['hello' => 'Hello World!']);
    }
}
