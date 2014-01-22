<?php

namespace SmartCore\Module\Menu\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use SmartCore\Module\Menu\Entity\Group;
use SmartCore\Module\Menu\Entity\Item;
use SmartCore\Module\Menu\Form\Type\GroupFormType;
use SmartCore\Module\Menu\Form\Type\ItemFormType;

class AdminController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $form = $this->createForm(new GroupFormType());

        if ($request->isMethod('POST') and $request->request->has('create')) {
            $form->submit($request);
            if ($form->isValid()) {
                $group = $form->getData();
                $group->setCreateByUserId($this->getUser()->getId());
                $em->persist($group);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Группа меню создана.'); // @todo translate
                return $this->redirect($this->generateUrl('smart_menu_admin_group', ['group_id' => $group->getId()]));
            }
        }

        return $this->render('MenuModule:Admin:index.html.twig', [
            'groups' => $em->getRepository('MenuModule:Group')->findAll(),
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Редактирование пункта меню
     *
     * @param Request $request
     * @param int $item_id
     * @return RedirectResponse|Response
     */
    public function itemAction(Request $request, $item_id)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        /** @var Item $item */
        $item = $em->find('MenuModule:Item', $item_id);

        $form = $this->createForm(new ItemFormType(), $item);

        if ($request->isMethod('POST')) {
            if ($request->request->has('update')) {
                $form->submit($request);
                if ($form->isValid()) {
                    $em->persist($form->getData());
                    $em->flush();

                    $this->get('tagcache')->deleteTag('module_menu');
                    $this->get('session')->getFlashBag()->add('notice', 'Пункт меню обновлён.'); // @todo translate
                    return $this->redirect($this->generateUrl('smart_menu_admin_group', ['group_id' => $item->getGroup()->getId()]));
                }
            } else if ($request->request->has('delete')) {
                // @todo безопасное удаление, в частности отключение из нод и удаление всех связаных пунктов меню.
                $em->remove($form->getData());
                $em->flush();

                $this->get('tagcache')->deleteTag('smart_module_menu');
                $this->get('session')->getFlashBag()->add('notice', 'Пункт меню удалён.');
                return $this->redirect($this->generateUrl('smart_menu_admin_group', ['group_id' => $item->getGroup()->getId()]));
            }
        }

        return $this->render('MenuModule:Admin:item.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Редактирование свойств группы меню.
     *
     * @param Request $request
     * @param int $group_id
     * @return RedirectResponse|Response
     */
    public function groupEditAction(Request $request, $group_id)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $group = $em->find('MenuModule:Group', $group_id);

        if (empty($group)) {
            return $this->redirect($this->generateUrl('smart_menu_admin'));
        }

        $form = $this->createForm(new GroupFormType(), $group);

        if ($request->isMethod('POST')) {
            if ($request->request->has('update')) {
                $form->submit($request);
                if ($form->isValid()) {
                    $em->persist($form->getData());
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Группа меню обновлена.'); // @todo translate
                    return $this->redirect($this->generateUrl('smart_menu_admin_group', ['group_id' => $group_id]));
                }
            } else if ($request->request->has('delete')) {
                // @todo безопасное удаление, в частности отключение из нод и удаление всех связаных пунктов меню.
                $em->remove($form->getData());
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Группа меню удалеа.');
                return $this->redirect($this->generateUrl('smart_menu_admin'));
            }
        }

        return $this->render('MenuModule:Admin:group_edit.html.twig', [
            'group' => $group,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * Редактирование группы меню.
     *
     * @param Request $request
     * @param int $group_id
     * @return RedirectResponse|Response
     */
    public function groupAction(Request $request, $group_id)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $group = $em->find('MenuModule:Group', $group_id);

        if (empty($group)) {
            return $this->redirect($this->generateUrl('smart_menu_admin'));
        }

        $form = $this->createForm(new ItemFormType(), new Item());

        if ($request->isMethod('POST')) {
            if ($request->request->has('create_item')) {
                $form->submit($request);
                if ($form->isValid()) {
                    /** @var Item $item */
                    $item = $form->getData();
                    $item->setCreateByUserId($this->getUser()->getId());
                    $item->setGroup($group);

                    $em->persist($item);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Пункт меню создан.'); // @todo translate

                    return $this->redirect($this->generateUrl('smart_menu_admin_group', ['group_id' => $group_id]));
                }
            }
        }

        return $this->render('MenuModule:Admin:group.html.twig', [
            'group' => $group,
            'form'  => $form->createView(),
        ]);
    }
}
