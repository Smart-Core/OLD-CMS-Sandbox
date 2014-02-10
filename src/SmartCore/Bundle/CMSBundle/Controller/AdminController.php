<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use SmartCore\Bundle\CMSBundle\Entity\Folder;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AdminController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('cms_admin_structure'));
    }

    /**
     * @return Response
     */
    public function notFoundAction()
    {
        return $this->render('CMSBundle:Admin:not_found.html.twig', []);
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

        return $this->render('CMSBundle:Admin:elfinder.html.twig', [
            'locale' => $parameters['locale'] ?: $request->getLocale(),
            'fullscreen' => true,
            'includeAssets' => $parameters['include_assets'],
        ]);
    }

    /**
     * @return Response
     */
    public function structureAction()
    {
        if (null === $this->get('cms.folder')->get(1)) {
            return $this->redirect($this->generateUrl('cms_admin_structure_folder_create'));
        }

        return $this->render('CMSBundle:Admin:structure.html.twig');
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
        $block->setCreateByUserId($this->getUser()->getId());

        $form = $engineBlock->createForm($block);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $engineBlock->update($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Блок создан.'); // @todo перевод

                return $this->redirect($this->generateUrl('cms_admin_structure_block'));
            }
        }

        return $this->render('CMSBundle:Admin:block_index.html.twig', [
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
            $sessionFlashBag = $this->get('session')->getFlashBag();
            if ($request->request->has('update')) {
                $form->submit($request);
                if ($form->isValid()) {
                    $engineBlock->update($form->getData());
                    $sessionFlashBag->add('success', 'Блок обновлён.'); // @todo перевод

                    return $this->redirect($this->generateUrl('cms_admin_structure_block'));
                }
            } elseif ($request->request->has('delete')) {
                $engineBlock->remove($form->getData());
                $sessionFlashBag->add('success', 'Блок удалён.'); // @todo перевод

                return $this->redirect($this->generateUrl('cms_admin_structure_block'));
            }
        }

        return $this->render('CMSBundle:Admin:block_edit.html.twig', [
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
                $form->submit($request);
                if ($form->isValid()) {
                    $engineFolder->update($form->getData());

                    $this->get('tagcache')->deleteTag('folder');
                    $this->get('session')->getFlashBag()->add('success', 'Папка создана.');

                    if ($request->query->has('redirect_to')) {
                        return $this->get('cms.router')->redirect($folder);
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('CMSBundle:Admin:folder_create.html.twig', [
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
                $form->submit($request);
                if ($form->isValid()) {
                    $engineFolder->update($form->getData());

                    $this->get('tagcache')->deleteTag('folder');
                    $this->get('session')->getFlashBag()->add('success', 'Папка обновлена.');

                    if ($request->query->has('redirect_to')) {
                        return $this->get('cms.router')->redirect($folder);
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->renderView('CMSBundle:Admin:folder_edit.html.twig', [
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
                $form->submit($request);
                if ($form->isValid()) {
                    /** @var $created_node \SmartCore\Bundle\CMSBundle\Entity\Node */
                    $created_node = $form->getData();

                    $engineNode->update($created_node);

                    // Если у модуля есть роутинги, тогда нода подключается к папке как роутер.
                    if ($this->container->has('cms.router_module.' . $created_node->getModule())) {
                        $folder = $created_node->getFolder();
                        $folder->setRouterNodeId($created_node->getId());
                        $this->get('cms.folder')->update($folder);
                    }

                    $this->get('tagcache')->deleteTag('node');
                    $this->get('session')->getFlashBag()->add('success', 'Нода создана.');

                    if ('front' === $request->query->get('redirect_to')) {
                        return $this->get('cms.router')->redirect($created_node);
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure_node_properties', ['id' => $created_node->getId()]));
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->render('CMSBundle:Admin:node_create.html.twig', [
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
                $form->submit($request);
                $form_properties->submit($request);
                if ($form->isValid() and $form_properties->isValid()) {
                    /** @var $updated_node \SmartCore\Bundle\CMSBundle\Entity\Node */
                    $updated_node = $form->getData();
                    $updated_node->setParams($form_properties->getData());
                    $engineNode->update($updated_node);

                    $this->get('tagcache')->deleteTag('node');
                    $this->get('session')->getFlashBag()->add('success', 'Нода обновлена.');

                    if ($request->query->has('redirect_to')) {
                        return $this->get('cms.router')->redirect($updated_node);
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } elseif ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->render('CMSBundle:Admin:node_edit.html.twig', [
            'form'            => $form->createView(),
            'form_properties' => $form_properties->createView(),
            'node'            => $node,
        ]);
    }

    /**
     * Отображение списка модулей.
     *
     * @return Response
     */
    public function moduleAction()
    {
        return $this->render('CMSBundle:Admin:module.html.twig', [
            'modules' => $this->get('cms.module')->all(),
        ]);
    }

    /**
     * @param string $filename
     */
    public function moduleInstallAction($filename = null)
    {
        $finder = new Finder();

        if (is_dir($this->get('kernel')->getRootDir() . '/../dist')) {
            $finder
                ->ignoreDotFiles(false)
                ->ignoreVCS(true)
                ->name('*.zip')
                ->in($this->get('kernel')->getRootDir() . '/../dist');
        } else {
            $finder = [];
        }

        // @todo убрать в сервис.
        if ( ! empty($filename)) {
            $this->get('cms.module')->install($filename);
        }

        return $this->render('CMSBundle:Admin:module_install.html.twig', [
            'modules'  => $finder,
            'filename' => $filename,
        ]);
    }

    /**
     * AJAX обновление БД.
     *
     * @param Request $request
     * @return Response
     */
    public function moduleInstallUpdateDbAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $application = new Application($this->get('kernel'));
            $application->setAutoExit(false);
            $input = new ArrayInput(['command' => 'doctrine:schema:update', "--force" => true]);
            $output = new BufferedOutput();

            $retval = $application->run($input, $output);

            return new Response('БД успешно обновлена.<p>' . $output->fetch() . '</p>' );
        } else {
            return new Response('Обновление БД возможно только через AJAX.');
        }
    }
}
