<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use SmartCore\Bundle\EngineBundle\Entity\Folder;

class AdminFolderController extends Controller
{
    /**
     * Редактирование папки.
     */
    public function editAction(Request $request, $id = 1)
    {
        $folder = $this->get('engine.folder')->get($id);

        if (empty($folder)) {
            return $this->redirect($this->generateUrl('cmf_admin_structure'));
        }

        $form = $this->get('engine.folder')->createForm($folder);

        // Для корневой папки удаляются некоторые поля формы
        if (1 == $id) {
            $form
                ->remove('uri_part')
                ->remove('parent_folder')
                ->remove('is_active')
                ->remove('is_file')
                ->remove('pos');
        }

        if ($request->isMethod('POST')) {
            if ($request->request->has('update')) {
                $form->bind($request);
                if ($form->isValid()) {
                    $this->get('engine.folder')->update($form->getData());

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse(['redirect' => $this->get('engine.folder')->getUri($folder->getId())]);
                    } else {
                        $this->get('session')->getFlashBag()->add('notice', 'Папка обновлена.');
                        return $this->redirect($this->generateUrl('cmf_admin_structure'));
                    }
                } else if ($request->isXmlHttpRequest()) {
                    // ld($form->getErrors()); // @todo разобраться почему не возвращаются ошибки.
                    return new JsonResponse(['notice' => 'Validation error.'], 400);
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('SmartCoreEngineBundle:Admin:structure_edit.html.twig', [
            'folder_id' => $id,
            'html_head_title' => 'Edit folder',
            'form' => $form->createView(),
            'form_action' => $this->generateUrl('cmf_admin_structure_folder', ['id' => $id]),
            'form_controls' => 'update',
            'allow_delete' => $id != 1 ? true : false,
        ]);
    }

    /**
     * Создание папки.
     */
    public function createAction(Request $request, $folder_pid = 1)
    {
        $folder = $this->get('engine.folder')->create();
        $folder->setCreateByUserId($this->getUser()->getId());
        $folder->setParentFolder($this->get('engine.folder')->get($folder_pid));

        $form = $this->get('engine.folder')->createForm($folder);

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->bind($request);
                if ($form->isValid()) {
                    $this->get('engine.folder')->update($form->getData());

                    if ($request->isXmlHttpRequest()) {
                        return new JsonResponse(['redirect' => $this->get('engine.folder')->getUri($folder->getId())]);
                    } else {
                        $this->get('session')->getFlashBag()->add('notice', 'Папка создана.');
                        return $this->redirect($this->generateUrl('cmf_admin_structure'));
                    }
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('SmartCoreEngineBundle:Admin:structure_edit.html.twig', [
            'html_head_title' => 'Create folder',
            'form' => $form->createView(),
            'form_action' => $this->generateUrl('cmf_admin_structure_folder_create'),
            'form_controls' => 'create',
        ]);
    }
}
