<?php

namespace SmartCore\Module\Unicat\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\RadBundle\Controller\Controller;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use SmartCore\Module\Unicat\Form\Type\ConfigurationFormType;
use Symfony\Component\HttpFoundation\Request;

class AdminUnicatController extends Controller
{
    public function indexAction(Request $request)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ConfigurationFormType());
        $form->add('create', 'submit', ['attr' => ['class' => 'btn-primary']]);

        return $this->render('UnicatModule:Admin:index.html.twig', [
            'configurations' => $em->getRepository('UnicatModule:UnicatConfiguration')->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $configuration
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function configurationAction($configuration)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $configuration = $this->get('unicat')->getConfiguration($configuration);

        if (empty($configuration)) {
            return $this->render('@CMS/Admin/not_found.html.twig');
        }

        return $this->render('UnicatModule:Admin:configuration.html.twig', [
            'configuration'     => $configuration,
            'attributes_groups' => $em->getRepository($configuration->getAttributesGroupClass())->findAll(),
            'attributes'        => $em->getRepository($configuration->getAttributeClass())->findAll(),
            'items'             => $em->getRepository($configuration->getItemClass())->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @param Request $request
     * @param string $configuration
     * @param int $default_category_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function itemCreateAction(Request $request, $configuration, $default_category_id = null)
    {
        $urm  = $this->get('unicat')->getConfigurationManager($configuration);

        $newItem = $urm->createItemEntity();
        $newItem->setUserId($this->getUser());

        if ($default_category_id) {
            $newItem->setCategories(new ArrayCollection([$urm->getCategoryRepository()->find($default_category_id)]));
        }

        $form = $urm->getItemCreateForm($newItem);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($form->get('cancel')->isClicked()) {
                    return $this->redirectToConfigurationAdmin($urm->getConfiguration());
                }

                $urm->createItem($form, $request);
                $this->get('session')->getFlashBag()->add('success', 'Запись создана');

                return $this->redirectToConfigurationAdmin($urm->getConfiguration());
            }
        }

        return $this->render('UnicatModule:Admin:item_create.html.twig', [
            'form'       => $form->createView(),
            'configuration' => $urm->getConfiguration(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param Request $request
     * @param string $configuration
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function itemEditAction(Request $request, $configuration, $id)
    {
        $urm  = $this->get('unicat')->getConfigurationManager($configuration);
        $form = $urm->getItemEditForm($urm->findItem($id));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToConfigurationAdmin($urm->getConfiguration());
            }

            if ($form->get('delete')->isClicked()) {
                $urm->removeItem($form->getData());
                $this->get('session')->getFlashBag()->add('success', 'Запись удалена');

                return $this->redirectToConfigurationAdmin($urm->getConfiguration());
            }

            if ($form->isValid() and $form->get('update')->isClicked() and $form->isValid()) {
                $urm->updateItem($form, $request);
                $this->get('session')->getFlashBag()->add('success', 'Запись обновлена');

                return $this->redirectToConfigurationAdmin($urm->getConfiguration());
            }
        }

        return $this->render('UnicatModule:Admin:item_edit.html.twig', [
            'form'       => $form->createView(),
            'configuration' => $urm->getConfiguration(), // @todo убрать, это пока для наследуемого шаблона.
        ]);
    }

    /**
     * @param UnicatConfiguration $configuration
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToConfigurationAdmin(UnicatConfiguration $configuration)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $url = $request->query->has('redirect_to')
            ? $request->query->get('redirect_to')
            : $this->generateUrl('unicat_admin.configuration', ['configuration' => $configuration->getName()]);

        return $this->redirect($url);
    }
}
