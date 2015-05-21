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
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $gallery = new Gallery();
        $gallery->setUserId($this->getUser());

        $form = $this->createForm(new GalleryFormType(), $gallery);
        $form->add('create', 'submit', ['attr' => ['class' => 'btn-success']]);

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
     * @param int $id
     *
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
            ->add('create album', 'submit', ['attr' => ['class' => 'btn-success']]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Album created successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin_gallery', ['id' => $id]);
            }
        }

        $folderPath = null;
        foreach ($this->get('cms.node')->findByModule('Gallery') as $node) {
            if ($node->getParam('gallery_id') === (int) $id) {
                $folderPath = $this->get('cms.folder')->getUri($node);

                break;
            }
        }

        return $this->render('GalleryModule:Admin:gallery.html.twig', [
            'form'       => $form->createView(),
            'folderPath' => $folderPath,
            'albums'     => $em->getRepository('GalleryModule:Album')->findBy(['gallery' => $id], ['id' => 'DESC']),
            'gallery'    => $gallery,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     *
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
        $form->add('update', 'submit', ['attr' => ['class' => 'btn-success']])
             ->add('cancel', 'submit');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('smart_module.gallery.admin');
            }

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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @todo pagination
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
                $this->addFlash('success', 'Photo uploaded successfully.');

                if ($album->getCoverImageId() == $album->getLastImageId()) {
                    $album->setCoverImageId($photo->getImageId());
                }

                $album
                    ->setPhotosCount($em->getRepository('GalleryModule:Photo')->countInAlbum($photo->getAlbum()))
                    ->setLastImageId($photo->getImageId())
                ;

                $this->persist($album, true);

                return $this->redirectToRoute('smart_module.gallery.admin_album', [
                    'id'         => $album->getId(),
                    'gallery_id' => $album->getGallery()->getId(),
                ]);
            }
        }

        $albumPath  = null;

        foreach ($this->get('cms.node')->findByModule('Gallery') as $node) {
            if ($node->getParam('gallery_id') === (int) $id) {
                $albumPath = $this->generateUrl('smart_module.gallery.album', [
                    '_folderPath' => $this->get('cms.folder')->getUri($node),
                    'id' => $id,
                ]);

                break;
            }
        }

        return $this->render('GalleryModule:Admin:album.html.twig', [
            'form'      => $form->createView(),
            'photos'    => $em->getRepository('GalleryModule:Photo')->findBy(['album' => $album], ['position' => 'DESC', 'id' => 'DESC']),
            'album'     => $album,
            'albumPath' => $albumPath,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param int $gallery_id
     *
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
        $form->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
             ->add('delete', 'submit', ['attr' => [ 'class' => 'btn btn-danger', 'onclick' => "return confirm('Вы уверены, что хотите удалить альбом?')" ]])
             ->add('cancel', 'submit');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('delete')->isClicked()) {
                if ($album->getPhotosCount() > 0) {
                    $this->addFlash('error', 'Удалить можно только пустой альбом.');

                    return $this->redirectToRoute('smart_module.gallery.admin_album_edit', ['id' => $album->getId(), 'gallery_id' => $gallery_id]);
                } else {
                    $this->addFlash('success', 'Album <b>'.$album.'</b> deleted successfully.');
                    $this->remove($album, true);
                }

                return $this->redirectToRoute('smart_module.gallery.admin_gallery', ['id' => $gallery_id]);
            }

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('smart_module.gallery.admin_gallery', ['id' => $gallery_id]);
            }

            if ($form->isValid()) {
                $this->persist($form->getData(), true);
                $this->addFlash('success', 'Album updated successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin_gallery', ['id' => $gallery_id]);
            }
        }

        return $this->render('GalleryModule:Admin:album_edit.html.twig', [
            'form'  => $form->createView(),
            'album' => $album,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param int $gallery_id
     * @param int $album_id
     * @param bool $set_as_cover
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function photoAction(Request $request, $id, $gallery_id, $album_id, $set_as_cover = false)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $photo = $em->find('GalleryModule:Photo', $id);

        if (empty($photo) or $photo->getAlbum()->getId() != $album_id or $photo->getAlbum()->getGallery()->getId() != $gallery_id) {
            throw $this->createNotFoundException();
        }

        $album = $photo->getAlbum();

        if ($set_as_cover) {
            $album->setCoverImageId($photo->getImageId());
            $this->persist($album, true);
            $this->addFlash('success', 'Photo set as cover successfully.');

            return $this->redirectToRoute('smart_module.gallery.admin_photo', ['album_id' => $album_id, 'gallery_id' => $gallery_id, 'id' => $id]);
        }

        $form = $this->createForm(new PhotoFormType(), $photo);
        $form
            ->remove('file')
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('delete', 'submit', ['attr' => [ 'class' => 'btn btn-danger', 'onclick' => "return confirm('Вы уверены, что хотите удалить фотографию?')" ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn-default', 'formnovalidate' => 'formnovalidate' ]])
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
                    $mc = $this->get('smart_media')->getCollection($album->getGallery()->getMediaCollection()->getId());
                    $mc->remove($photo->getImageId());

                    $this->remove($photo, true);
                    $this->addFlash('success', 'Photo deleted successfully.');

                    $album->setPhotosCount($em->getRepository('GalleryModule:Photo')->countInAlbum($album));

                    if ($album->getCoverImageId() == $id) {
                        $lastPhoto = $em->getRepository('GalleryModule:Photo')->findOneBy(['album' => $album], ['id' => 'DESC']);
                        $album->setCoverImageId(empty($lastPhoto) ? null : $lastPhoto->getImageId());
                    }

                    $this->persist($album, true);

                    return $this->redirectToRoute('smart_module.gallery.admin_album', ['id' => $album_id, 'gallery_id' => $gallery_id]);
                }

                $this->persist($photo, true);
                $this->addFlash('success', 'Photo updated successfully.');

                return $this->redirectToRoute('smart_module.gallery.admin_album', [
                    'id'         => $album_id,
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
