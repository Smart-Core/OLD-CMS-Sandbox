<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminStructureController extends Controller
{
    public function indexAction()
    {
        return $this->renderView('SmartCoreEngineBundle:Admin:structure.html.twig', []);
    }

    /**
     * Отображение структуры в виде дерева.
     */
    public function showTreeAction($folder_id = null, $node_id = null)
    {
        return $this->renderView('SmartCoreEngineBundle:Admin:tree.html.twig', [
            'folder_id' => $folder_id,
            'node_id'   => $node_id,
        ]);
    }
}
