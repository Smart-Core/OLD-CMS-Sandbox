<?php

namespace SmartCore\Module\Catalog\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminCatalogController extends Controller
{
    public function indexAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        return $this->render('CatalogModule:Admin:index.html.twig', [
            'repositories' => $em->getRepository('UnicatBundle:UnicatRepository')->findAll(),
            //'form'    => $form->createView(),
        ]);
    }

    public function repositoryAction($name)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        return $this->render('CatalogModule:Admin:repository.html.twig', [
            'repository' => $em->getRepository('UnicatBundle:UnicatRepository')->findOneBy(['name' => $name]),
            //'form'    => $form->createView(),
        ]);
    }
}
