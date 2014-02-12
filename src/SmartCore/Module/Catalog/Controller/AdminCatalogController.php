<?php

namespace SmartCore\Module\Catalog\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminCatalogController extends Controller
{
    public function indexAction()
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        return $this->render('CatalogModule:Admin:index.html.twig', [
            'repositories' => $em->getRepository('UnicatBundle:UnicatRepository')->findAll(),
        ]);
    }

    public function repositoryAction($repository)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('UnicatBundle:UnicatRepository')->findOneBy(['name' => $repository]);

        return $this->render('CatalogModule:Admin:repository.html.twig', [
            'repository' => $repository,
            'properties_groups' => $em->getRepository($repository->getEntitiesNamespace() . 'PropertyGroup')->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $structure_id
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction(Request $request, $structure_id, $id)
    {
        $unicat = $this->get('unicat');

        $structure = $unicat->getStructure($structure_id);
        $category  = $unicat->getCategory($structure, $id);

        $form = $unicat->getCategoryEditForm($category);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('smart_module.catalog_structure_admin', ['id' => $structure_id]));
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $unicat->updateCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория обновлена');

                return $this->redirect($this->generateUrl('smart_module.catalog_structure_admin', ['id' => $structure_id]));
            }

            if ($form->get('delete')->isClicked() and $form->isValid()) {
                $unicat->deleteCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория удалена');

                return $this->redirect($this->generateUrl('smart_module.catalog_structure_admin', ['id' => $structure_id]));
            }
        }

        return $this->render('CatalogModule:Admin:category.html.twig', [
            'category'   => $category,
            'form'       => $form->createView(),
            'repository' => $structure->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
            'structure'  => $structure,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function structureAction(Request $request, $id)
    {
        $unicat = $this->get('unicat');
        $structure  = $unicat->getStructure($id);

        $form = $unicat->getCategoryCreateForm($structure);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $unicat->createCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория создана');

                return $this->redirect($this->generateUrl('smart_module.catalog_structure_admin', ['id' => $id]));
            }
        }

        return $this->render('CatalogModule:Admin:structure.html.twig', [
            'form'       => $form->createView(),
            'repository' => $structure->getRepository(), // @todo убрать, это пока для наследуемого шаблона.
            'structure'  => $structure,
        ]);
    }
}
