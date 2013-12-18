<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class AdminBlockController extends Controller
{
    /**
     * Отображение списка всех блоков, а также форма добавления нового.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
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

        return $this->render('SmartCoreEngineBundle:Admin:block.html.twig', [
            'all_blocks'  => $engineBlock->all(),
            'form_create' => $form->createView(),
        ]);
    }

    /**
     * Редактирование блока.
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, $id = 0)
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

        return $this->render('SmartCoreEngineBundle:Admin:block.html.twig', [
            'block_id'  => $id,
            'form_edit' => $form->createView(),
        ]);
    }
}
