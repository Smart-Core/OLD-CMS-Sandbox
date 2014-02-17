<?php

namespace SmartCore\Module\Catalog\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogController extends Controller
{
    use NodeTrait;

    /**
     * @var int
     */
    protected $repository_id;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $urm = $this->get('unicat')->getRepositoryManager($this->repository_id);

        return $this->render('CatalogModule::catalog.html.twig', [
            'items' => $urm->findAllItems(['id' => 'DESC']),
        ]);
    }
}
