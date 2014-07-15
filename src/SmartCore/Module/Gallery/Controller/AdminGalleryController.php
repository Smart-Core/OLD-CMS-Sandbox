<?php

namespace SmartCore\Module\Gallery\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Module\Gallery\Entity\Album;
use SmartCore\Module\Gallery\Entity\Gallery;
use SmartCore\Module\Gallery\Entity\Photo;
use SmartCore\Module\Gallery\Form\Type\AlbumFormType;
use SmartCore\Module\Gallery\Form\Type\GalleryFormType;
use SmartCore\Module\Gallery\Form\Type\PhotoFormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class AdminGalleryController extends Controller
{
    public function indexAction(Request $request)
    {
        $gallery = new Gallery();
        $gallery->setUserId($this->getUser());

        $form = $this->createForm(new GalleryFormType(), $gallery);
        $form->add('create', 'submit');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Gallery created successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin');
            }
        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        return $this->render('GalleryModule:Admin:index.html.twig', [
            'form'      => $form->createView(),
            'galleries' => $em->getRepository('GalleryModule:Gallery')->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function galleryAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $gallery = $em->find('GalleryModule:Gallery', $id);

        if (empty($gallery)) {
            throw $this->createNotFoundException();
        }

        $album = new Album();
        $album
            ->setGallery($gallery)
            ->setUserId($this->getUser())
        ;

        $form = $this->createForm(new AlbumFormType(), $album);
        $form
            ->remove('is_enabled')
            ->add('create', 'submit');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Album created successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin_gallery', ['id' => $id]);
            }
        }

        return $this->render('GalleryModule:Admin:gallery.html.twig', [
            'form'      => $form->createView(),
            'albums'    => $em->getRepository('GalleryModule:Album')->findAll(),
            'gallery'   => $gallery,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function galleryEditAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $gallery = $em->find('GalleryModule:Gallery', $id);

        if (empty($gallery)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(new GalleryFormType(), $gallery);
        $form->add('update', 'submit');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Gallery updated successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin');
            }
        }

        return $this->render('GalleryModule:Admin:gallery_edit.html.twig', [
            'form'      => $form->createView(),
            'gallery'   => $gallery,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param int $gallery_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function albumAction(Request $request, $id, $gallery_id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $album = $em->find('GalleryModule:Album', $id);

        if (empty($album) or $album->getGallery()->getId() != $gallery_id) {
            throw $this->createNotFoundException();
        }

        $photo = new Photo();
        $photo
            ->setUserId($this->getUser())
            ->setAlbum($album)
        ;

        $form = $this->createForm(new PhotoFormType(), $photo);
        $form->add('upload', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var Photo $photo */
                $photo = $form->getData();

                if ($photo->getFile() instanceof UploadedFile) {
                    $mc = $this->get('smart_media')->getCollection($album->getGallery()->getMediaCollection()->getId());
                    $photo->setImageId($mc->upload($photo->getFile()));
                }

                $this->persist($photo, true);
                $this->addFlash('success', 'Photo updated successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin_album', [
                    'id' => $album->getId(),
                    'gallery_id' => $album->getGallery()->getId(),
                ]);
            }
        }

        return $this->render('GalleryModule:Admin:album.html.twig', [
            'form'      => $form->createView(),
            'photos'    => $em->getRepository('GalleryModule:Photo')->findBy(['album' => $album], ['position' => 'DESC', 'id' => 'DESC']),
            'album'     => $album,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param int $gallery_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function albumEditAction(Request $request, $id, $gallery_id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $album = $em->find('GalleryModule:Album', $id);

        if (empty($album) or $album->getGallery()->getId() != $gallery_id) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(new AlbumFormType(), $album);
        $form->add('update', 'submit');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Album updated successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin_gallery', ['id' => $gallery_id]);
            }
        }

        return $this->render('GalleryModule:Admin:album_edit.html.twig', [
            'form'      => $form->createView(),
            'album'     => $album,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param int $gallery_id
     * @param int $album_id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function photoAction(Request $request, $id, $gallery_id, $album_id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $photo = $em->find('GalleryModule:Photo', $id);

        if (empty($photo) or $photo->getAlbum()->getId() != $album_id or $photo->getAlbum()->getGallery()->getId() != $gallery_id) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(new PhotoFormType(), $photo);
        $form
            ->remove('file')
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('delete', 'submit', ['attr' => [ 'class' => 'btn btn-danger', 'onclick' => "return confirm('Вы уверены, что хотите удалить фотографию?')" ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]])
        ;

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var Photo $photo */
                $photo = $form->getData();

                if ($form->get('cancel')->isClicked()) {
                    return $this->redirectToRoute('smart_module.gallery.admin_album', ['id' => $album_id, 'gallery_id' => $gallery_id]);
                }

                if ($form->get('delete')->isClicked()) {
                    $mc = $this->get('smart_media')->getCollection($photo->getAlbum()->getGallery()->getMediaCollection()->getId());
                    $mc->remove($photo->getImageId());

                    $this->remove($photo, true);
                    $this->addFlash('success', 'Photo deleted successfully.');

                    return $this->redirectToRoute('smart_module.gallery.admin_album', ['id' => $album_id, 'gallery_id' => $gallery_id]);
                }

                $this->persist($photo, true);
                $this->addFlash('success', 'Photo updated successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin_album', [
                    'id' => $album_id,
                    'gallery_id' => $gallery_id,
                ]);
            }
        }

        return $this->render('GalleryModule:Admin:photo.html.twig', [
            'form'  => $form->createView(),
            'photo' => $photo,
        ]);
    }
}
