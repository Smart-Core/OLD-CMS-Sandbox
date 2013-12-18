<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminModuleController extends Controller
{
    public function indexAction()
    {
        return $this->render('SmartCoreEngineBundle:Admin:module.html.twig', [
            'modules' => $this->get('engine.module')->all(),
        ]);
    }

    /**
     * Управление модулем.
     *
     * @param Request $request
     * @param string $module
     * @param string $slug
     */
    public function manageAction(Request $request, $module, $slug = null)
    {
        // Удаление _node_id из форм.
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            foreach ($data as $key => $value) {
                if ($key == '_node_id') {
                    unset($data['_node_id']);
                    break;
                }

                if (is_array($value) and array_key_exists('_node_id', $value)) {
                    unset($data[$key]['_node_id']);
                    break;
                }
            }
            foreach ($data as $key => $value) {
                $request->request->set($key, $value);
            }
        }

        return $this->forward("{$module}Module:Admin:index", ['slug' => $slug]);
    }
}
