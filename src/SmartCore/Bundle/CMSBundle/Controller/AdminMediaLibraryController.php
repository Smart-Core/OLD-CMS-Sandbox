<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\MediaBundle\Form\Type\CollectionFormType;
use SmartCore\Bundle\MediaBundle\Form\Type\StorageFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMediaLibraryController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $collections = $em->getRepository('SmartMediaBundle:Collection')->findAll();
        $storages    = $em->getRepository('SmartMediaBundle:Storage')->findAll();

        return $this->render('CMSBundle:AdminMediaLibrary:index.html.twig', [
            'collections'   => $collections,
            'storages'      => $storages,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editStorageAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $storage = $em->find('SmartMediaBundle:Storage', $id);

        if (empty($storage)) {
            return $this->redirectToRoute('cms_admin_config_media');
        }

        $form = $this->createForm(new StorageFormType(), $storage);
        $form->add('update', 'submit', ['attr' => ['class' => 'btn btn-success']]);
        $form->add('cancel', 'submit', ['attr' => ['class' => 'btn', 'formnovalidate' => 'formnovalidate']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('cms_admin_config_media');
            }

            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Хранилище обновлено');

                return $this->redirectToRoute('cms_admin_config_media');
            }
        }

        return $this->render('CMSBundle:AdminMediaLibrary:edit_storage.html.twig', [
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editCollectionAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $collection = $em->find('SmartMediaBundle:Collection', $id);

        if (empty($collection)) {
            return $this->redirectToRoute('cms_admin_config_media');
        }

        $form = $this->createForm(new CollectionFormType(), $collection);
        $form->add('update', 'submit', ['attr' => ['class' => 'btn btn-success']]);
        $form->add('cancel', 'submit', ['attr' => ['class' => 'btn', 'formnovalidate' => 'formnovalidate']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('cms_admin_config_media');
            }

            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Коллекция обновлена');

                return $this->redirectToRoute('cms_admin_config_media');
            }
        }

        return $this->render('CMSBundle:AdminMediaLibrary:edit_collection.html.twig', [
            'form'   => $form->createView(),
        ]);
    }
}
