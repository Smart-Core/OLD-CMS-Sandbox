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
            } else if ($request->request->has('delete')) {
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
        $folder->setParentFolder($engineFolder->get($folder_pid));

        $form = $engineFolder->createForm($folder);

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->submit($request);
                if ($form->isValid()) {
                    $engineFolder->update($form->getData());

                    $this->get('session')->getFlashBag()->add('success', 'Папка создана.');

                    if (isset($_GET['redirect_to'])) {
                        return $this->redirect($engineFolder->getUri($folder->getId()));
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } else if ($request->request->has('delete')) {
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

                    if (isset($_GET['redirect_to'])) {
                        return $this->redirect($engineFolder->getUri($folder->getId()));
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } else if ($request->request->has('delete')) {
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
    public function nodeAction($id, $slug = null)
    {
        $node = $this->get('cms.node')->get($id);

        $controller = $this->get('cms.router')->matchModuleAdmin($node->getModule(), '/' . $slug);
        $controller['_node'] = $node;

        $subRequest = $this->container->get('request')->duplicate([], null, $controller);

        $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);

        if ($response->isRedirection() and isset($_GET['redirect_to'])) {
            return $this->redirect($_GET['redirect_to']);
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
        $engineNode = $this->get('cms.node');
        $node = $engineNode->create();
        $node->setCreateByUserId($this->getUser()->getId());
        $node->setFolder($this->get('cms.folder')->get($folder_pid));

        $form = $engineNode->createForm($node);

        if ($request->isMethod('POST')) {
            if ($request->request->has('create')) {
                $form->submit($request);
                if ($form->isValid()) {
                    /** @var $created_node \SmartCore\Bundle\CMSBundle\Entity\Node */
                    $created_node = $form->getData();
                    $engineNode->update($created_node);

                    if (isset($_GET['redirect_to']) and $_GET['redirect_to'] == 'front') {
                        return $this->redirect($this->get('cms.folder')->getUri($created_node->getFolderId()));
                    }

                    $this->get('session')->getFlashBag()->add('success', 'Нода создана.');
                    return $this->redirect($this->generateUrl('cms_admin_structure_node_properties', ['id' => $created_node->getId()]));
                }
            } else if ($request->request->has('delete')) {
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

                    $this->get('session')->getFlashBag()->add('success', 'Нода обновлена.');

                    if (isset($_GET['redirect_to'])) {
                        return $this->redirect($this->get('cms.folder')->getUri($updated_node->getFolderId()));
                    }

                    return $this->redirect($this->generateUrl('cms_admin_structure'));
                }
            } else if ($request->request->has('delete')) {
                die('@todo');
            }
        }

        return $this->render('CMSBundle:Admin:node_edit.html.twig', [
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
        $finder
            ->ignoreDotFiles(false)
            ->ignoreVCS(true)
            ->name('*.zip')
            ->in($this->get('kernel')->getRootDir() . '/../dist');

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
