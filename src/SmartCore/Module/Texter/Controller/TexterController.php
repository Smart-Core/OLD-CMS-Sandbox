<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\EngineBundle\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TexterController extends Controller
{
    /**
     * Экшен по умолчанию.
     *
     * @param integer $item_id
     */
    public function indexAction($item_id = null)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $item = $em->find('TexterModule:Item', $item_id ? $item_id : $this->text_item_id);

        $this->View
            ->setEngine('echo')
            ->set('text', $item->getText());

        foreach ($item->getMeta() as $key => $value) {
            $this->get('html')->meta($key, $value);
        }

        $response = new Response($this->View);

        if ($this->isEip()) {
            $response->setFrontControls([
                'edit' => [
                    'title'   => 'Редактировать',
                    'descr'   => 'Текстовый блок',
                    'uri'     => $this->generateUrl('cmf_admin_node', ['id' => $this->node->getId()]),
                    'default' => true,
                ],
            ]);
        }

        return $response;
    }

    /**
     * Обработчик POST данных.
     *
     * @return Response
     */
    public function postAction(Request $request, $item_id = null)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $item = $em->getRepository('TexterModule:Item')->find($item_id ? $item_id : $this->text_item_id);

        if (empty($item)) {
            return new JsonResponse([
                'status' => 'INVALID',
                'message' => 'Item does not exist', // 'Запись не найдена.'
            ], 400);
        }

        $data = $request->request->get('texter');
        $item->setText($data['text']);
        $item->setMeta($data['meta']);

        try {
            $em->persist($item);
            $em->flush();
        } catch (\Exception $e) {
            $errors = [];
            if ($this->get('kernel')->isDebug()) {
                $errors['sql_debug'] = $e->getMessage();
            }

            return new JsonResponse([
                'status' => 'INVALID',
                'message' => 'Error update.', //'Ошибка при сохранении данных.',
                'errors' => $errors
            ], 400);
        }

        $url = $request->request->get('referer', $request->headers->get('referer'));

        if (empty($url)) {
            $url = $this->get('engine.folder')->getUri($this->node->getFolder()->getId());
        }

        return new JsonResponse([
            'status' => 'OK',
            'message' => 'Text updated successful.', //'Текст обновлён',
            'redirect' => $url,
        ]);
//        ], 302, ['Location', $url]);
    }
}
