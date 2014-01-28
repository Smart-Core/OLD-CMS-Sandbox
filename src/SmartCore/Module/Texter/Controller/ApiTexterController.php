<?php

namespace SmartCore\Module\Texter\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiTexterController extends Controller
{
    /**
     * @param null $item_id
     * @return JsonResponse
     */
    public function jsonAction($item_id = null)
    {
        $item = $this->get('smart_module.texter')->get($item_id ? $item_id : $this->text_item_id, $this->node->getId());

        if ($item) {
            return new JsonResponse([
                'status'  => 'success',
                'message' => '',
                'data'    => $item->getText(),
            ], 200);
        }

        return new JsonResponse([
            'status'  => 'error',
            'message' => 'Not found',
            'data'    => [],
        ], 404);
    }

    /**
     * @param null $item_id
     * @return JsonResponse
     */
    public function htmlAction($item_id = null)
    {
        $item = $this->get('smart_module.texter')->get($item_id ? $item_id : $this->text_item_id, $this->node->getId());

        if ($item) {
            return new Response($item->getText());
        }

        return $this->createNotFoundException('Not found');
    }
}
