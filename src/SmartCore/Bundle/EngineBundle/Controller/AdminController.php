<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use SmartCore\Bundle\EngineBundle\Entity\Folder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('cmf_admin_structure'));
    }

    /**
     * @return Response
     */
    public function notFoundAction()
    {
        return $this->render('SmartCoreEngineBundle:Admin:not_found.html.twig', []);
    }

    /**
     * Renders Elfinder.
     *
     * @param Request $request
     * @return Response
     */
    public function elfinderAction(Request $request)
    {
        $parameters = $this->container->getParameter('fm_elfinder');
        //$editor = $parameters['editor'];
        $locale = $parameters['locale'] ?: $request->getLocale();
        //$fullscreen = $parameters['fullscreen'];
        $includeAssets = $parameters['include_assets'];
        //$compression = $parameters['compression'];
        //$prefix = ($compression ? '/compressed' : '');

        return $this->render('SmartCoreEngineBundle:Admin:elfinder.html.twig', [
            'locale' => $locale,
            'fullscreen' => true,
            'includeAssets' => $includeAssets,
        ]);
    }

    /**
     * @return Response
     */
    public function structureAction()
    {
        return $this->render('SmartCoreEngineBundle:Admin:structure.html.twig');
    }

    /**
     * Отображение списка всех блоков, а также форма добавления нового.
     *
     * @param Request $request
     * @return Response
     */
    public function blockIndexAction(Request $request)
    {
        $engineBlock = $this->get('engine.block');
        $block = $engineBlock->create();
        $block->setCreateByUserId($this->getUser()->getId());

        $form = $engineBlock->createForm($block);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $engineBlock->update($form->getData());
                $this->get('session')->getFlashBag()->add('notice', 'Блок создан.'); // @todo перевод
                return $this->redirect($this->generateUrl('cmf_admin_structure_block'));
            }
        }

        return $this->render('SmartCoreEngineBundle:Admin:block_index.html.twig', [
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
        $engineBlock = $this->get('engine.block');
        $block = $engineBlock->get($id);

        if (empty($block)) {
            return $this->redirect($this->generateUrl('cmf_admin_structure_block'));
        }

        $form = $engineBlock->createForm($block);

        if ($request->isMethod('POST')) {
            $sessionFlashBag = $this->get('session')->getFlashBag();
            if ($request->request->has('update')) {
                $form->submit($request);
                if ($form->isValid()) {
                    $engineBlock->update($form->getData());
                    $sessionFlashBag->add('notice', 'Блок обновлён.'); // @todo перевод
                    return $this->redirect($this->generateUrl('cmf_admin_structure_block'));
                }
            } else if ($request->request->has('delete')) {
                $engineBlock->remove($form->getData());
                $sessionFlashBag->add('notice', 'Блок удалён.'); // @todo перевод
                return $this->redirect($this->generateUrl('cmf_admin_structure_block'));
            }
        }

        return $this->render('SmartCoreEngineBundle:Admin:block_edit.html.twig', [
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
        $engineFolder = $this->get('engine.folder');

        /** @var Folder $folder */
        $folder = $engineFolder->create();
        $folder->setCreateByUserId($this->getUser()->getId());
        $folder->setParentFolder($engineFolder->get($folder_pid));

        $form = $engineFolder->createForm($folder);

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->submit($request);
                if ($form->isValid()) {
                    $engineFolder->update($form->getData());

                    $this->get('session')->getFlashBag()->add('notice', 'Папка создана.');

                    if (isset($_GET['redirect_to']) or isset($_GET['return'])) {
                        return $this->redirect($engineFolder->getUri($folder->getId()));
                    }

                    return $this->redirect($this->generateUrl('cmf_admin_structure'));
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('SmartCoreEngineBundle:Admin:folder_create.html.twig', [
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
        $engineFolder = $this->get('engine.folder');

        /** @var Folder $folder */
        $folder = $engineFolder->get($id);

        if (empty($folder)) {
            return $this->redirect($this->generateUrl('cmf_admin_structure'));
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
                $form->submit($request);
                if ($form->isValid()) {
                    $engineFolder->update($form->getData());

                    $this->get('session')->getFlashBag()->add('notice', 'Папка обновлена.');

                    if (isset($_GET['redirect_to']) or isset($_GET['return'])) {
                        return $this->redirect($engineFolder->getUri($folder->getId()));
                    }

                    return $this->redirect($this->generateUrl('cmf_admin_structure'));
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('SmartCoreEngineBundle:Admin:folder_edit.html.twig', [
            'allow_delete'  => $id != 1 ? true : false,
            'form'          => $form->createView(),
        ]);
    }

    /**
     * @todo !!!
     *
     * @param int $id
     * @param string $slug
     * @return Response
     */
    public function nodeAction($id, $slug = null)
    {
        $response = $this->forward("$id:Admin:index", ['slug' => $slug]);

        if ($response->isRedirection()) {
            if (isset($_GET['return'])) {
                return $this->redirect($_GET['return']);
            }
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
        $engineNode = $this->get('engine.node');
        $node = $engineNode->create();
        $node->setCreateByUserId($this->getUser()->getId());
        $node->setFolder($this->get('engine.folder')->get($folder_pid));

        $form = $engineNode->createForm($node);

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->submit($request);
                if ($form->isValid()) {
                    /** @var $created_node \SmartCore\Bundle\EngineBundle\Entity\Node */
                    $created_node = $form->getData();
                    $engineNode->update($created_node);

                    if (isset($_GET['redirect_to']) and $_GET['redirect_to'] == 'front') {
                        return $this->redirect($this->get('engine.folder')->getUri($created_node->getFolderId()));
                    }

                    $this->get('session')->getFlashBag()->add('notice', 'Нода создана.');
                    return $this->redirect($this->generateUrl('cmf_admin_structure_node_properties', ['id' => $created_node->getId()]));
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->render('SmartCoreEngineBundle:Admin:node_create.html.twig', [
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
                $form_properties->submit($request);
                if ($form->isValid() and $form_properties->isValid()) {
                    /** @var $updated_node \SmartCore\Bundle\EngineBundle\Entity\Node */
                    $updated_node = $form->getData();
                    $updated_node->setParams($form_properties->getData());
                    $engineNode->update($updated_node);

                    $this->get('session')->getFlashBag()->add('notice', 'Нода обновлена.');

                    if (isset($_GET['redirect_to']) or isset($_GET['return'])) {
                        return $this->redirect($this->get('engine.folder')->getUri($updated_node->getFolderId()));
                    }

                    return $this->redirect($this->generateUrl('cmf_admin_structure'));
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->render('SmartCoreEngineBundle:Admin:node_edit.html.twig', [
            'form'            => $form->createView(),
            'form_properties' => $form_properties->createView(),
        ]);
    }

    /**
     * Отображение списка модулей.
     *
     * @return Response
     */
    public function moduleAction()
    {
        return $this->render('SmartCoreEngineBundle:Admin:module.html.twig', [
            'modules' => $this->get('engine.module')->all(),
        ]);
    }

    /**
     * Управление модулем.
     *
     * @param Request $request
     * @param string $module
     * @param string $slug
     */
    public function moduleManageAction(Request $request, $module, $slug = null)
    {
        // Удаление _node_id из форм.
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            foreach ($data as $key => $value) {
                if ($key == '_node_id') {
                    unset($data['_node_id']);
                    break;
                }

                if (is_array($value) and array_key_exists('_node_id', $value)) {
                    unset($data[$key]['_node_id']);
                    break;
                }
            }
            foreach ($data as $key => $value) {
                $request->request->set($key, $value);
            }
        }

        return $this->forward("{$module}Module:Admin:index", ['slug' => $slug]);
    }
}
