<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\CMSBundle\Module\CacheTrait;
use SmartCore\Module\Texter\Entity\Item;
use SmartCore\Module\Texter\Entity\ItemHistory;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    use CacheTrait;

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function itemAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->find('TexterModule:Item', $id);

        if ($request->isMethod('POST')) {
            $data = $request->request->get('texter');
            $item->setText($data['text']);
            $item->setMeta($data['meta']);

            $this->getCacheService()->deleteTag('smart_module_texter');

            if ($item->getEditor()) {
                $item->setText($this->get('html.tidy')->prettifyFragment($item->getText()));
            }

            try {
                $em->persist($item);
                $em->flush($item);

                $history = new ItemHistory($item);
                $em->persist($history);
                $em->flush($history);

                $this->get('session')->getFlashBag()->add('success', 'Текст обновлён'); // @todo перевод.
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

    /**
     * @param $id
     */
    public function historyAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $items = $em->getRepository('TexterModule:ItemHistory')->findBy(
            ['item_id' => $id],
            ['created' => 'DESC']
        );

        return $this->render('TexterModule:Admin:history.html.twig', [
            'items' => $items,
        ]);
    }

    public function rollbackAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $historyItem = $em->find('TexterModule:ItemHistory', $id);

        if ($historyItem) {
            $item = $em->find('TexterModule:Item', $historyItem->getItemId());
            $item
                ->setEditor($historyItem->getEditor())
                ->setLocale($historyItem->getLocale())
                ->setMeta($historyItem->getMeta())
                ->setText($historyItem->getText())
                ->setText($historyItem->getText())
                ->setUserId($historyItem->getUserId())
            ;

            $em->persist($item);
            $em->flush($item);

            $this->get('session')->getFlashBag()->add('success', 'Откат успешно выполнен.'); // @todo перевод.
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Непредвиженная ошибка при выполнении отката'); // @todo перевод.
        }

        return $this->redirect($this->generateUrl('smart_texter_admin'));
    }
}
