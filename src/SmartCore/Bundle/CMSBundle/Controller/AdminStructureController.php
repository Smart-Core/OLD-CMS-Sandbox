<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Entity\Folder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AdminStructureController extends Controller
{
    /**
     * @return Response
     */
    public function structureAction()
    {
        if (null === $this->get('cms.folder')->get(1)) {
            return $this->redirect($this->generateUrl('cms_admin_structure_folder_create'));
        }

        return $this->render('CMSBundle:AdminStructure:structure.html.twig');
    }

    /**
     * Отображение списка всех блоков, а также форма добавления нового.
     *
     * @param Request $request
     * @return Response
     */
    public function blockIndexAction(Request $request)
    {
        $engineBlock = $this->get('cms.block');
        $block = $engineBlock->create();
        $block->setUserId($this->getUser());

        $form = $engineBlock->createForm($block);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $engineBlock->update($form->getData());
                $this->addFlash('success', 'Блок создан.'); // @todo перевод

                return $this->redirect($this->generateUrl('cms_admin_structure_block'));
            }
        }

        return $this->render('CMSBundle:AdminStructure:block_index.html.twig', [
            'all_blocks' => $engineBlock->all(),
            'form'       => $form->createView(),
        ]);
    }

    /**
     * Редактирование блока.
     *
     * @param Request $request
     * @param int $id
     * @return Response|RedirectResponse
     */
    public function blockEditAction(Request $request, $id = 0)
    {
        $engineBlock = $this->get('cms.block');
        $block = $engineBlock->get($id);

        if (empty($block)) {
            return $this->redirect($this->generateUrl('cms_admin_structure_block'));
        }

        $form = $engineBlock->createForm($block);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($request->request->has('update')) {
                if ($form->isValid()) {
                    $engineBlock->update($form->getData());
                    $this->addFlash('success', 'Блок обновлён.'); // @todo перевод

                    return $this->redirect($this->generateUrl('cms_admin_structure_block'));
                }
            } elseif ($request->request->has('delete')) {
                /** @var \SmartCore\Bundle\CMSBundle\Entity\Block $block */
                $block = $form->getData();

                if ('content' == $block->getName()) {
                    $this->addFlash('error', 'Нельзя удалить блок content'); // @todo перевод
                } elseif (0 < $this->get('doctrine.orm.entity_manager')->getRepository('CMSBundle:Node')->countInBlock($block)) {
                    $this->addFlash('error', 'Нельзя удалить блок пока в него включены ноды'); // @todo перевод
                } else {
                    $engineBlock->remove($block);
                    $this->addFlash('success', 'Блок удалён.'); // @todo перевод

                    return $this->redirect($this->generateUrl('cms_admin_structure_block'));
                }
            }
        }

        return $this->render('CMSBundle:AdminStructure:block_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Создание папки.
     *
     * @param Request $request
     * @param int $folder_pid
     * @return Response|RedirectResponse
     */
    public function folderCreateAction(Request $request, $folder_pid = 1)
    {
        $engineFolder = $this->get('cms.folder');

        /** @var Folder $folder */
        $folder = $engineFolder->create();
        $folder->setCreateByUserId($this->getUser()->getId());

        $parent = $engineFolder->get($folder_pid);

        if (empty($parent)) {
            $folder->setTitle($this->get('translator')->trans('Homepage'));
            $folder->setHasInheritNodes(true);
        } else {
            $folder->setParentFolder($engineFolder->get($folder_pid));
        }

        $form = $engineFolder->createForm($folder);

        // Для корневой папки удаляются некоторые поля формы
        if (empty($parent)) {
            $form
                ->remove('uri_part')
                ->remove('parent_folder')
                ->remove('router_node_id')
                ->remove('is_active')
                ->remove('is_file')
                ->remove('pos');
        }

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $engineFolder->update($form->getData());

                    $this->get('tagcache')->deleteTag('folder');
                    $this->addFlash('success', 'Папка создана.');

                    if ($request->query->has('redirect_to')) {
                        return $this->get('cms.router')->redirect($folder);
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('CMSBundle:AdminStructure:folder_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Редактирование папки.
     *
     * @param Request $request
     * @param int $id
     * @return string|RedirectResponse
     */
    public function folderEditAction(Request $request, $id = 1)
    {
        $engineFolder = $this->get('cms.folder');

        /** @var Folder $folder */
        $folder = $engineFolder->get($id);

        if (empty($folder)) {
            return $this->redirect($this->generateUrl('cms_admin_structure'));
        }

        $form = $engineFolder->createForm($folder);

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
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $engineFolder->update($form->getData());

                    $this->get('tagcache')->deleteTag('folder');
                    $this->addFlash('success', 'Папка обновлена.');

                    if ($request->query->has('redirect_to')) {
                        return $this->get('cms.router')->redirect($folder);
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('CMSBundle:AdminStructure:folder_edit.html.twig', [
            'allow_delete'  => $id != 1 ? true : false,
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @param int $id
     * @param string|null $slug
     * @return Response
     */
    public function nodeAction(Request $request, $id, $slug = null)
    {
        $node = $this->get('cms.node')->get($id);

        $controller = $this->get('cms.router')->matchModuleAdmin($node->getModule(), '/' . $slug);
        $controller['_node'] = $node;

        $subRequest = $this->container->get('request')->duplicate($request->query->all(), null, $controller);

        $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);

        if ($response->isRedirection() and $request->query->has('redirect_to')) {
            return $this->redirect($request->query->get('redirect_to'));
        }

        return $response;
    }

    /**
     * Создание новой ноды.
     *
     * @param Request $request
     * @param int $folder_pid
     * @return RedirectResponse|Response
     */
    public function nodeCreateAction(Request $request, $folder_pid = 1)
    {
        if (null === $folder = $this->get('cms.folder')->get($folder_pid)) {
            return $this->redirect($this->generateUrl('cms_admin_structure_folder_create'));
        }

        $engineNode = $this->get('cms.node');
        $node = $engineNode->create();
        $node->setCreateByUserId($this->getUser()->getId())
            ->setFolder($folder);

        $form = $engineNode->createForm($node);

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    /** @var $createdNode \SmartCore\Bundle\CMSBundle\Entity\Node */
                    $createdNode = $form->getData();

                    $engineNode->update($createdNode);

                    // Если у модуля есть роутинги, тогда нода подключается к папке как роутер.
                    $folder = $createdNode->getFolder();
                    if ($this->container->has('cms.router_module.' . $createdNode->getModule()) and !$folder->getRouterNodeId()) {
                        $folder->setRouterNodeId($createdNode->getId());
                        $this->get('cms.folder')->update($folder);
                    }

                    $this->get('tagcache')->deleteTag('node');
                    $this->addFlash('success', 'Нода создана.');

                    if ('front' === $request->query->get('redirect_to')) {
                        return $this->get('cms.router')->redirect($createdNode);
                    }

                    return $this->redirectToRoute('cms_admin_structure_node_properties', ['id' => $createdNode->getId()]);
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->render('CMSBundle:AdminStructure:node_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Редактирование ноды.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function nodeEditAction(Request $request, $id)
    {
        $engineNode = $this->get('cms.node');
        $node = $engineNode->get($id);

        if (empty($node)) {
            return $this->redirect($this->generateUrl('cms_admin_structure'));
        }

        $form = $engineNode->createForm($node);
        $form_properties = $this->createForm($engineNode->getPropertiesFormType($node->getModule()), $node->getParams());

        $form->remove('module');

        if ($request->isMethod('POST')) {
            if ($request->request->has('update')) {
                $form->handleRequest($request);
                $form_properties->handleRequest($request);
                if ($form->isValid() and $form_properties->isValid()) {
                    /** @var $updatedNode \SmartCore\Bundle\CMSBundle\Entity\Node */
                    $updatedNode = $form->getData();
                    $updatedNode->setParams($form_properties->getData());
                    $engineNode->update($updatedNode);

                    $this->get('tagcache')->deleteTag('node');
                    $this->addFlash('success', 'Нода обновлена.');

                    if ($request->query->has('redirect_to')) {
                        return $this->get('cms.router')->redirect($updatedNode);
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->render('CMSBundle:AdminStructure:node_edit.html.twig', [
            'form'            => $form->createView(),
            'form_properties' => $form_properties->createView(),
            'node'            => $node,
        ]);
    }
}
