<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * RESTful API.
 *
 * Формат ответа:
 *
 * 'status'  => 'error', 'success', 'redirect'.
 * 'message' => Вспомогательное сообщение.
 * 'data'    => Произвольные данные.
 */
class ApiEngineController extends Controller
{
    /**
     * @param Request $request
     * @param string $slug
     *
     * @return Response
     */
    public function nodeAction(Request $request, $node_id, $slug = null)
    {
        // @todo сделать проверку, доступна ли нода в папке т.к. папка может быть выключенной или пользователь не имеет к ней прав.

        $node = $this->get('cms.node')->get($node_id);

        if (null === $node) {
            return $this->notFoundAction();
        }

        $controller = $this->get('cms.router')->matchModuleApi($node->getModule(), '/'.$slug, $request);

        if (null === $controller) {
            return $this->notFoundAction();
        }

        $controller['_node'] = $node;

        $subRequest = $this->container->get('request')->duplicate(
            $request->query->all(),
            $request->request->all(),
            $controller,
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all()
        );

        return $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * @return JsonResponse
     */
    public function notFoundAction()
    {
        return new JsonResponse([
            'status'  => 'error',
            'message' => 'Некорректный запрос.',
            'data'    => [],
        ], 404);
    }
}
