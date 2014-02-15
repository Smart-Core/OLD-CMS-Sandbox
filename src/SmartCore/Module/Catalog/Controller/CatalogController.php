<?php

namespace SmartCore\Module\Catalog\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller
{
    use NodeTrait;

    /**
     * @var int
     */
    protected $repository_id;

    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $unicat = $this->get('unicat');
        $repository = $unicat->getRepository($this->repository_id);

        return $this->render('CatalogModule::catalog.html.twig', [
            'items'      => $unicat->findAllItems($repository),
            'repository' => $repository,
        ]);
    }
}
