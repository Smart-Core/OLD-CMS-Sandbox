<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminNodeController extends Controller
{
    /**
     * @todo !!!
     *
     * @param Request $request
     * @param $id
     * @param null $slug
     * @return Response
     */
    public function nodeAction(Request $request, $id, $slug = null)
    {
        return $this->forward("$id:Admin:index", ['slug' => $slug]);
    }

    /**
     * Редактирование ноды.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, $id)
    {
        $engineNode = $this->get('engine.node');
        $node = $engineNode->get($id);

        if (empty($node)) {
            return $this->redirect($this->generateUrl('cmf_admin_structure'));
        }

        $form = $engineNode->createForm($node);
        $form_properties = $this->createForm($engineNode->getPropertiesFormType($node->getModule()), $node->getParams());

        $form->remove('module');

        if ($request->isMethod('POST')) {
            if ($request->request->has('update')) {
                $form->submit($request);
                $form_properties->bind($request);
                if ($form->isValid() and $form_properties->isValid()) {
                    /** @var $updated_node \SmartCore\Bundle\EngineBundle\Entity\Node */
                    $updated_node = $form->getData();
                    $updated_node->setParams($form_properties->getData());
                    $engineNode->update($updated_node);

                    if ($request->isXmlHttpRequest()) {
                        // @todo проверять referer, и если нода по прежнему находится в наследованном пути, то редиректиться в реферер.
                        return new JsonResponse(['redirect' => $this->get('engine.folder')->getUri($updated_node->getFolder()->getId())]);
                    } else {
                        $this->get('session')->getFlashBag()->add('notice', 'Нода обновлена.');
                        return $this->redirect($this->generateUrl('cmf_admin_structure'));
                    }
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('SmartCoreEngineBundle:Admin:node_edit.html.twig', [
            'node_id' => $id,
            'html_head_title' => 'Edit node',
            'form' => $form->createView(),
            'form_action' => $this->generateUrl('cmf_admin_structure_node_properties', ['id' => $id]),
            'form_controls' => 'update',
            'form_properties' => $form_properties->createView(),
        ]);
    }

    /**
     * Создание новой ноды.
     *
     * @param Request $request
     * @param int $folder_pid
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request, $folder_pid = 1)
    {
        $engineNode = $this->get('engine.node');
        $node = $engineNode->create();
        $node->setCreateByUserId($this->getUser()->getId());
        $node->setFolder($this->get('engine.folder')->get($folder_pid));

        $form = $engineNode->createForm($node);

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->submit($request);
                if ($form->isValid()) {
                    /** @var $updated_node \SmartCore\Bundle\EngineBundle\Entity\Node */
                    $created_node = $form->getData();
                    $engineNode->update($created_node);

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse(['redirect' => $this->get('engine.folder')->getUri($created_node->getFolder()->getId())]);
                    } else {
                        $this->get('session')->getFlashBag()->add('notice', 'Нода создана.');
                        return $this->redirect($this->generateUrl('cmf_admin_structure_node_properties', ['id' => $created_node->getId()]));
                    }
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('SmartCoreEngineBundle:Admin:structure_edit.html.twig', [
            'html_head_title' => 'Create node',
            'form' => $form->createView(),
            'form_action' => $this->generateUrl('cmf_admin_structure_node_create'),
            'form_controls' => 'create',
        ]);
    }
}
