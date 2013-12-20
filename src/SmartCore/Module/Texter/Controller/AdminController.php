<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\EngineBundle\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @param Request $request
     * @param null $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $request, $slug = null)
    {
        // @todo сделать роутинг
        if (!empty($slug)) {
            $parts = explode('/', $slug);

            return $this->itemAction($request, $slug);
        }
        // ----------------------------------------------------

        if (!empty($this->node)) {
            return $this->itemAction($request, $this->text_item_id);
        }

        $em = $this->get('doctrine.orm.default_entity_manager');

        return $this->render('TexterModule:Admin:index.html.twig', [
            'items' => $em->getRepository('TexterModule:Item')->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param $item_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function itemAction(Request $request, $item_id)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $item = $em->find('TexterModule:Item', $item_id);

        if ($request->isMethod('POST')) {
            $data = $request->request->get('texter');
            $item->setText($data['text']);
            $item->setMeta($data['meta']);

            try {
                $em->persist($item);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Текст обновлён');

                return $this->redirect($this->generateUrl('cmf_admin_module_manage', [
                    'module' => 'Texter',
                ]));
            } catch (\Exception $e) {
                $errors = ['sql_debug' => $e->getMessage()];

                $this->get('session')->getFlashBag()->add('errors', $errors);

                return $this->redirect($this->generateUrl('cmf_admin_module_manage', [
                    'module' => 'Texter',
                    'slug'   => $item_id,
                ]));
            }
        }

        return $this->render('TexterModule:Admin:edit.html.twig', [
            '_node_id' => empty($this->node) ?: $this->node->getId(),
            'item'     => $item,
        ]);
    }
}
