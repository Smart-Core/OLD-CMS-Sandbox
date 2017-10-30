<?php

namespace SandboxSiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Smart\CoreBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Entity\Node;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class HelloController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $this->get('html')
            ->doctype('xhtml')
            ->lang('en')
            ->title('hi :)')
        ;

//        $themes = $this->get('liip_theme.active_theme');
//        $themes->setThemes(['default', 'light']);
//        $themes->setName('light');

//        ld($themes->getName());
//        ld($themes->getThemes());

//        ld($em->getRepository('SandboxSiteBundle\Entity\Catalog\Category')->findAll());

//        ld(unserialize('a:3:{i:0;a:8:{s:2:"id";i:1;s:4:"name";s:6:"rubric";s:5:"table";s:31:"unicat_categories_s1_e1_rubrics";s:7:"entries";s:1:"1";s:5:"descr";s:14:"Рубрики";s:3:"pos";s:1:"1";s:15:"create_datetime";s:19:"2011-10-19 07:34:54";s:7:"reqired";s:1:"0";}i:1;a:8:{s:2:"id";i:3;s:4:"name";s:3:"geo";s:5:"table";s:25:"unicat_structure_geo_base";s:7:"entries";s:6:"single";s:5:"descr";s:14:"Регионы";s:3:"pos";s:1:"3";s:15:"create_datetime";s:19:"2011-10-19 07:39:51";s:7:"reqired";s:1:"1";}i:2;a:8:{s:2:"id";i:4;s:4:"name";s:4:"tags";s:5:"table";s:11:"unicat_tags";s:7:"reqired";s:1:"0";s:7:"entries";s:5:"multi";s:5:"descr";s:4:"Tags";s:3:"pos";s:1:"4";s:15:"create_datetime";s:19:"2011-11-30 11:31:51";}}'));
//        ld(unserialize('a:1:{i:0;a:8:{s:2:"id";i:1;s:4:"name";s:17:"subscribe_rubrics";s:5:"table";s:41:"unicat_categories_s2_e3_subscribe_rubrics";s:7:"reqired";s:1:"0";s:7:"entries";s:5:"multi";s:5:"descr";s:31:"Рубрики рассылок";s:3:"pos";s:1:"1";s:15:"create_datetime";s:19:"2011-12-07 03:18:39";}}'));

//        $router = $this->get('router');
//        ld($router);

        /** @var Router $moduleRouter */
        $moduleRouter = $this->get('cms.router_module.unicat');

        /*
        ld($moduleRouter->match('/123.html'));

        try {
            ld($moduleRouter->match('/page/html/'));
        } catch (ResourceNotFoundException $e) {
            ld('/page/html/ - NotFound');
        }

        ld($moduleRouter->match('/'));
        */

//        return $this->forward('TexterModuleBundle:Test:hello', ['text' => 'yahoo :)'])->getContent();
//        return $this->forward('2:Texter:index')->getContent();
//        return $this->forward('12:SimpleNews:index')->getContent();
//        return $this->forward('12::index')->getContent();
//        return $this->forward(5)->getContent();

//        $tmp = $this->forward(8);
//        $tmp = $this->forward('MenuModuleBundle:Menu:index');
//        ld(get_class($tmp));
//        ld($tmp->getContentRaw());
//        echo $tmp->getContent();

        /*
        $activeTheme = $this->get('liip_theme.active_theme');
        $activeTheme->setThemes(['web', 'tablet', 'phone']);
        $activeTheme->setName('phone');
        */

//        $nodes = $em->getRepository('CMSBundle:Node')->findInInheritanceFolder($folder_id);

//        $b = $em->getRepository('CMSBundle:Region')->find(2);
//        $folder = $em->find('CMSBundle:Folder', 1);
//        $b->setFolders($folder);
//        ld($b->getFolders());

//        $em->persist($folder);
//        $em->flush();

//        $repo = $em->getRepository('CMSBundle:Node')->findIn([4, 6, 5, 1]);
//        $node = $em->find('CMSBundle:Node', 8);
//        ld($repo);

/* @var $node Node */
        //ld($this->get('cms.folder')->getUri($node->getFolder()->getId()));
        return $this->render('HtmlBundle::test.html.twig', ['hello' => 'Hello World!']);
    }
}
