<?php

namespace SmartCore\Module\Unicat\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use Symfony\Component\HttpFoundation\Request;

class AdminStructureController extends Controller
{
    /**
     * @param Request $request
     * @param int $structure_id
     * @param int $id
     * @param string $configuration
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function categoryEditAction(Request $request, $structure_id, $id, $configuration)
    {
        $urm    = $this->get('unicat')->getConfigurationManager($configuration);
        $unicat = $this->get('unicat'); // @todo перевести всё на $urm.

        $structure = $urm->getStructure($structure_id);
        $category  = $unicat->getCategory($structure, $id);

        $form = $unicat->getCategoryEditForm($category);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToStructureAdmin($urm->getConfiguration(), $structure_id);
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $unicat->updateCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория обновлена');

                return $this->redirectToStructureAdmin($urm->getConfiguration(), $structure_id);
            }

            if ($form->has('delete') and $form->get('delete')->isClicked()) {
                $unicat->deleteCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория удалена');

                return $this->redirectToStructureAdmin($urm->getConfiguration(), $structure_id);
            }
        }

        return $this->render('UnicatModule:AdminStructure:category_edit.html.twig', [
            'configuration' => $structure->getConfiguration(), // @todo убрать, это пока для наследуемого шаблона.
            'category'      => $category,
            'form'          => $form->createView(),
            'structure'     => $structure,
        ]);
    }

    /**
     * @param string $configuration
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($configuration)
    {
        $configuration = $this->get('unicat')->getConfiguration($configuration);

        if (empty($configuration)) {
            return $this->render('@CMS/Admin/not_found.html.twig');
        }

        return $this->render('UnicatModule:AdminStructure:index.html.twig', [
            'configuration'     => $configuration,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param string|int $configuration
     * @param int|null $parent_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function structureAction(Request $request, $id, $configuration, $parent_id = null)
    {
        $urm        = $this->get('unicat')->getConfigurationManager($configuration);
        $unicat     = $this->get('unicat'); // @todo перевести всё на $urm.
        $structure  = $unicat->getStructure($id);

        $parent_category = $parent_id ? $urm->getCategoryRepository()->find($parent_id) : null;

        $form = $unicat->getCategoryCreateForm($structure, [], $parent_category);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $unicat->createCategory($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Категория создана');

                return $this->redirectToStructureAdmin($urm->getConfiguration(), $id);
            }
        }

        return $this->render('UnicatModule:AdminStructure:structure.html.twig', [
            'configuration' => $structure->getConfiguration(), // @todo убрать, это пока для наследуемого шаблона.
            'form'          => $form->createView(),
            'structure'     => $structure,
        ]);
    }

    /**
     * @param Request $request
     * @param string $configuration
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, $configuration)
    {
        $urm = $this->get('unicat')->getConfigurationManager($configuration);
        $form = $urm->getStructureCreateForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('unicat_admin.structures_index', ['configuration' => $configuration]));
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $urm->updateStructure($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Структура создана');

                return $this->redirect($this->generateUrl('unicat_admin.structures_index', ['configuration' => $configuration]));
            }
        }

        return $this->render('UnicatModule:AdminStructure:create.html.twig', [
            'form'          => $form->createView(),
            'configuration' => $urm->getConfiguration(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param string|int $configuration
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id, $configuration)
    {
        $urm = $this->get('unicat')->getConfigurationManager($configuration);
        $form = $urm->getStructureEditForm($urm->getStructure($id));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('unicat_admin.structures_index', ['configuration' => $configuration]));
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $urm->updateStructure($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Структура обновлена');

                return $this->redirect($this->generateUrl('unicat_admin.structures_index', ['configuration' => $configuration]));
            }
        }

        return $this->render('UnicatModule:AdminStructure:edit.html.twig', [
            'form'          => $form->createView(),
            'configuration' => $urm->getConfiguration(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param UnicatConfiguration $configuration
     * @param int $structure_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToStructureAdmin(UnicatConfiguration $configuration, $structure_id)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $url = $request->query->has('redirect_to')
            ? $request->query->get('redirect_to')
            : $this->generateUrl('unicat_admin.structure', ['id' => $structure_id, 'configuration' => $configuration->getName()]);

        return $this->redirect($url);
    }
}
