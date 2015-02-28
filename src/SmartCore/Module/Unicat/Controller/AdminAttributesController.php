<?php

namespace SmartCore\Module\Unicat\Controller;

use Knp\RadBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminAttributesController extends Controller
{
    public function indexAction($configuration)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $unicat = $this->get('unicat');
        $configuration = $unicat->getConfiguration($configuration);

        return $this->render('UnicatModule:AdminAttributes:index.html.twig', [
            'configuration'     => $configuration, // @todo убрать, это пока для наследуемого шаблона.
            'attributes_groups' => $em->getRepository($configuration->getPropertiesGroupClass())->findAll(),
            'attributes'        => $em->getRepository($configuration->getPropertyClass())->findAll(),

        ]);
    }

    /**
     * @param Request $request
     * @param string|int $configuration
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createGroupAction(Request $request, $configuration)
    {
        $urm = $this->get('unicat')->getConfigurationManager($configuration);
        $form = $urm->getPropertiesGroupCreateForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('unicat_admin.properties_index', ['configuration' => $configuration]));
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $urm->updatePropertiesGroup($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Группа атрибутов создана');

                return $this->redirect($this->generateUrl('unicat_admin.properties_index', ['configuration' => $configuration]));
            }
        }

        return $this->render('UnicatModule:AdminAttributes:create_group.html.twig', [
            'form'          => $form->createView(),
            'configuration' => $urm->getConfiguration(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string $configuration
     * @param int $group_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function groupAction(Request $request, $configuration, $group_id)
    {
        $unicat = $this->get('unicat');
        $configuration = $unicat->getConfiguration($configuration);
        $properties = $unicat->getProperties($configuration, $group_id);
        $form = $unicat->getPropertyCreateForm($configuration, $group_id);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $unicat->createProperty($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Свойство создано');

                return $this->redirect($this->generateUrl('unicat_admin.properties', ['configuration' => $configuration->getName(), 'group_id' => $group_id]));
            }
        }

        return $this->render('UnicatModule:AdminAttributes:group.html.twig', [
            'form'       => $form->createView(),
            'properties' => $properties,
            'group'      => $unicat->getPropertiesGroup($configuration, $group_id),
            'configuration' => $configuration, // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string $configuration
     * @param int $group_id
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attributeEditAction(Request $request, $configuration, $group_id, $id)
    {
        $unicat = $this->get('unicat');
        $configuration = $unicat->getConfiguration($configuration);

        $property = $unicat->getProperty($configuration, $id);
        $form = $unicat->getPropertyEditForm($configuration, $property);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirect($this->generateUrl('unicat_admin.properties', ['configuration' => $configuration->getName(), 'group_id' => $group_id]));
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $unicat->updateProperty($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Свойство обновлено');

                return $this->redirect($this->generateUrl('unicat_admin.properties', ['configuration' => $configuration->getName(), 'group_id' => $group_id]));
            }

            if ($form->has('delete') and $form->get('delete')->isClicked()) {
                $unicat->deleteProperty($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Свойство удалено');

                return $this->redirect($this->generateUrl('unicat_admin.properties', ['configuration' => $configuration->getName(), 'group_id' => $group_id]));
            }
        }

        return $this->render('UnicatModule:AdminAttributes:attribute_edit.html.twig', [
            'form'       => $form->createView(),
            'configuration' => $configuration, // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }
}
