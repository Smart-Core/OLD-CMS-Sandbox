<?php

namespace SmartCore\Module\Gallery\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Component\HttpFoundation\Response;

class GalleryController extends Controller
{
    use NodeTrait;

    /**
     * @var int
     */
    protected $gallery_id;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        switch ($em->find('GalleryModule:Gallery', $this->gallery_id)->getOrderAlbumsBy()) {
            case 1:
                $albumsOrderBy = ['position' => 'ASC'];
                break;
            default:
                $albumsOrderBy = ['id' => 'DESC'];
        }

        $albums = $em->getRepository('GalleryModule:Album')->findBy(['is_enabled' => true, 'gallery' => $this->gallery_id], $albumsOrderBy);

        $this->node->addFrontControl('manage_gallery')
            ->setTitle('Управление фотогалереей')
            ->setUri($this->generateUrl('smart_module.gallery.admin_gallery', ['id' => $this->gallery_id]));

        return $this->render('GalleryModule::index.html.twig', [
            'albums'  => $albums,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function albumAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $album = $em->getRepository('GalleryModule:Album')->find($id);

        if (empty($album) or $this->gallery_id != $album->getGallery()->getId() or $album->isDisabled()) {
            throw $this->createNotFoundException();
        }

        $this->node->addFrontControl('manage_album')
            ->setTitle('Редактировать фотографии')
            ->setUri($this->generateUrl('smart_module.gallery.admin_album', ['id' => $album->getId(), 'gallery_id' => $this->gallery_id]));

        $this->get('cms.breadcrumbs')->add($album->getId(), $album->getTitle());

        $photos = $em->getRepository('GalleryModule:Photo')->findBy(['album' => $album], ['id' => 'DESC']);

        return $this->render('GalleryModule::photos.html.twig', [
            'photos'  => $photos,
        ]);
    }
}
