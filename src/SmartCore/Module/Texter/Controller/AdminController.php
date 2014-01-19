<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\CMSBundle\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        if (!empty($this->node)) {
            return $this->itemAction($request, $this->text_item_id);
        }

        return $this->render('TexterModule:Admin:index.html.twig', [
            'items' => $this->getDoctrine()->getRepository('TexterModule:Item')->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function itemAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->find('TexterModule:Item', $id);

        if ($request->isMethod('POST')) {
            $data = $request->request->get('texter');
            $item->setText($data['text']);
            $item->setMeta($data['meta']);

            try {
                $em->persist($item);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Текст обновлён'); // @todo перевод.

                return $this->redirect($this->generateUrl('smart_texter_admin'));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('errors', ['sql_debug' => $e->getMessage()]);

                return $this->redirect($this->generateUrl('smart_texter_admin_edit', ['id' => $id]));
            }
        }

        return $this->render('TexterModule:Admin:edit.html.twig', [
            '_node_id' => empty($this->node) ?: $this->node->getId(),
            'item'     => $item,
        ]);
    }
}
