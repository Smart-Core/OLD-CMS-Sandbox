<?php

namespace SmartCore\Module\Catalog\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminCatalogController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        return $this->render('CatalogModule:Admin:index.html.twig', [
            'repositories' => $em->getRepository('UnicatBundle:UnicatRepository')->findAll(),
        ]);
    }

    /**
     * @param string $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function repositoryAction($repository)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $repository = $this->get('unicat')->getRepository($repository);

        return $this->render('CatalogModule:Admin:repository.html.twig', [
            'properties_groups' => $em->getRepository($repository->getPropertiesGroupClass())->findAll(),
            'properties'        => $em->getRepository($repository->getPropertyClass())->findAll(),
            'items'             => $em->getRepository($repository->getItemClass())->findBy([], ['id' => 'DESC']),
            'repository'        => $repository,
        ]);
    }

    /**
     * @param Request $request
     * @param int $structure_id
     * @param int $id
     * @param string $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function categoryEditAction(Request $request, $structure_id, $id, $repository)
    {
        $urm    = $this->get('unicat')->getRepositoryManager($repository);
        $unicat = $this->get('unicat'); // @todo перевести всё на $urm.

        $structure = $urm->getStructure($structure_id);
        $category  = $unicat->getCategory($structure, $id);

        $form = $unicat->getCategoryEditForm($category);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToStructureAdmin($urm->getRepository(), $structure_id);
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $unicat->updateCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория обновлена');

                return $this->redirectToStructureAdmin($urm->getRepository(), $structure_id);
            }

            if ($form->has('delete') and $form->get('delete')->isClicked()) {
                $unicat->deleteCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория удалена');

                return $this->redirectToStructureAdmin($urm->getRepository(), $structure_id);
            }
        }

        return $this->render('CatalogModule:Admin:category_edit.html.twig', [
            'category'   => $category,
            'form'       => $form->createView(),
            'repository' => $structure->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
            'structure'  => $structure,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param string|int $repository
     * @param int|null $parent_category_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function structureAction(Request $request, $id, $repository, $parent_category_id = null)
    {
        $urm        = $this->get('unicat')->getRepositoryManager($repository);
        $unicat     = $this->get('unicat'); // @todo перевести всё на $urm.
        $structure  = $unicat->getStructure($id);

        $parent_category = $parent_category_id ? $urm->getCategoryRepository()->find($parent_category_id) : null;

        $form = $unicat->getCategoryCreateForm($structure, [], $parent_category);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $unicat->createCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория создана');

                return $this->redirectToStructureAdmin($urm->getRepository(), $id);
            }
        }

        return $this->render('CatalogModule:Admin:structure.html.twig', [
            'form'       => $form->createView(),
            'repository' => $structure->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
            'structure'  => $structure,
        ]);
    }

    /**
     * @param Request $request
     * @param $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function structureCreateAction(Request $request, $repository)
    {
        $urm = $this->get('unicat')->getRepositoryManager($repository);
        $form = $urm->getStructureCreateForm();

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('smart_module.catalog_repository_admin', ['repository' => $repository]));
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $urm->updateStructure($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Структура создана');

                return $this->redirect($this->generateUrl('smart_module.catalog_repository_admin', ['repository' => $repository]));
            }
        }

        return $this->render('CatalogModule:Admin:structure_create.html.twig', [
            'form'       => $form->createView(),
            'repository' => $urm->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param string|int $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function structureEditAction(Request $request, $id, $repository)
    {
        $urm = $this->get('unicat')->getRepositoryManager($repository);
        $form = $urm->getStructureEditForm($urm->getStructure($id));

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('smart_module.catalog_repository_admin', ['repository' => $repository]));
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $urm->updateStructure($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Структура обновлена');

                return $this->redirect($this->generateUrl('smart_module.catalog_repository_admin', ['repository' => $repository]));
            }
        }

        return $this->render('CatalogModule:Admin:structure_edit.html.twig', [
            'form'       => $form->createView(),
            'repository' => $urm->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string|int $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function propertiesGroupCreateAction(Request $request, $repository)
    {
        $urm = $this->get('unicat')->getRepositoryManager($repository);
        $form = $urm->getPropertiesGroupCreateForm();

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('smart_module.catalog_repository_admin', ['repository' => $repository]));
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $urm->updatePropertiesGroup($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Группа свойств создана');

                return $this->redirect($this->generateUrl('smart_module.catalog_repository_admin', ['repository' => $repository]));
            }
        }

        return $this->render('CatalogModule:Admin:properties_group_create.html.twig', [
            'form'       => $form->createView(),
            'repository' => $urm->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string $repository
     * @param int $group_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function propertiesAction(Request $request, $repository, $group_id)
    {
        $unicat = $this->get('unicat');
        $repository = $unicat->getRepository($repository);
        $properties = $unicat->getProperties($repository, $group_id);
        $form = $unicat->getPropertyCreateForm($repository, $group_id);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $unicat->createProperty($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Свойство создано');

                return $this->redirect($this->generateUrl('smart_module.catalog_properties_admin', ['repository' => $repository->getName(), 'group_id' => $group_id]));
            }
        }

        return $this->render('CatalogModule:Admin:properties.html.twig', [
            'form'       => $form->createView(),
            'properties' => $properties,
            'group'      => $unicat->getPropertiesGroup($repository, $group_id),
            'repository' => $repository, // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string $repository
     * @param int $group_id
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function propertyAction(Request $request, $repository, $group_id, $id)
    {
        $unicat = $this->get('unicat');
        $repository = $unicat->getRepository($repository);

        $property = $unicat->getProperty($repository, $id);
        $form = $unicat->getPropertyEditForm($repository, $property);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('smart_module.catalog_properties_admin', ['repository' => $repository->getName(), 'group_id' => $group_id]));
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $unicat->updateProperty($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Свойство обновлено');

                return $this->redirect($this->generateUrl('smart_module.catalog_properties_admin', ['repository' => $repository->getName(), 'group_id' => $group_id]));
            }

            if ($form->has('delete') and $form->get('delete')->isClicked()) {
                $unicat->deleteProperty($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Свойство удалено');

                return $this->redirect($this->generateUrl('smart_module.catalog_properties_admin', ['repository' => $repository->getName(), 'group_id' => $group_id]));
            }
        }

        return $this->render('CatalogModule:Admin:property.html.twig', [
            'form'       => $form->createView(),
            'repository' => $repository, // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string $repository
     * @param int $default_category_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function itemCreateAction(Request $request, $repository, $default_category_id = null)
    {
        $urm  = $this->get('unicat')->getRepositoryManager($repository);

        $newItem = $urm->createItemEntity();
        $newItem->setUserId($this->getUser());

        if ($default_category_id) {
            $newItem->setCategories(new ArrayCollection([$urm->getCategoryRepository()->find($default_category_id)]));
        }

        $form = $urm->getItemCreateForm($newItem);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                if ($form->get('cancel')->isClicked()) {
                    return $this->redirectToRepositoryAdmin($urm->getRepository());
                }

                $urm->createItem($form, $request);
                $this->get('session')->getFlashBag()->add('success', 'Запись создана');

                return $this->redirectToRepositoryAdmin($urm->getRepository());
            }
        }

        return $this->render('CatalogModule:Admin:item_create.html.twig', [
            'form'       => $form->createView(),
            'repository' => $urm->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string $repository
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemEditAction(Request $request, $repository, $id)
    {
        $urm  = $this->get('unicat')->getRepositoryManager($repository);
        $form = $urm->getItemEditForm($urm->findItem($id));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRepositoryAdmin($urm->getRepository());
            }

            if ($form->get('delete')->isClicked()) {
                $urm->removeItem($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Запись удалена');

                return $this->redirectToRepositoryAdmin($urm->getRepository());
            }

            if ($form->isValid() and $form->get('update')->isClicked() and $form->isValid()) {
                $urm->updateItem($form, $request);
                $this->get('session')->getFlashBag()->add('success', 'Запись обновлена');

                return $this->redirectToRepositoryAdmin($urm->getRepository());
            }
        }

        return $this->render('CatalogModule:Admin:item_edit.html.twig', [
            'form'       => $form->createView(),
            'repository' => $urm->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param UnicatRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToRepositoryAdmin(UnicatRepository $repository)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $url = $request->query->has('redirect_to')
            ? $request->query->get('redirect_to')
            : $this->generateUrl('smart_module.catalog_repository_admin', ['repository' => $repository->getName()]);

        return $this->redirect($url);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $structure_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToStructureAdmin(UnicatRepository $repository, $structure_id)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $url = $request->query->has('redirect_to')
            ? $request->query->get('redirect_to')
            : $this->generateUrl('smart_module.catalog_structure_admin', ['id' => $structure_id, 'repository' => $repository->getName()]);

        return $this->redirect($url);
    }
}
