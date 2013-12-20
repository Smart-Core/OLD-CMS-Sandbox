<?php

namespace SmartCore\Bundle\EngineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SmartCore\Bundle\EngineBundle\Engine\View;

class EngineController extends Controller
{
    /**
     * Коллекция фронтальных элементов управления.
     * @var array
     */
    protected $cmf_front_controls;

    /**
     * @param Request $request
     * @param string $slug
     * @return Response
     */
    public function runAction(Request $request, $slug)
    {
        $router_data = $this->get('engine.folder')->getRouterData($slug);

        $nodes_list = $this->get('engine.node')->buildList($router_data);

        $this->View
            ->setOptions([
                'comment'   => 'Базовый шаблон',
                'template'  => $router_data['template'],
            ])
            ->set('blocks', new View([
                'comment'   => 'Блоки',
                'engine'    => 'echo',
            ]));

        \Profiler::start('buildModulesData');
        $this->buildModulesData($nodes_list);
        \Profiler::end('buildModulesData');

        //\Profiler::start('EngineController::runAction body');
        $this->buildBaseHtml($request);
        //\Profiler::end('EngineController::runAction body');

        \Profiler::start('Response');
        return new Response($this->container->get('templating')->render("::{$this->View->getTemplateName()}.html.twig", [
            'block' => $this->View->blocks,
        ]), $router_data['status'] );
    }

    /**
     * Временный метод...
     *
     * @todo отрефакторить!!!
     */
    protected function buildBaseHtml(Request $request)
    {
        $this->get('html')->title('Smart Core CMS (based on Symfony2 Framework)');

        // @todo убрать в ini-шник шаблона.
        $this->get('html')->meta('viewport', 'width=device-width, initial-scale=1.0');

        if ($this->get('security.context')->isGranted('ROLE_ADMIN') && !$request->isXmlHttpRequest()) {
            /*
            $cmf_front_controls = array(
                'node' => array(
                    '__node_5' => array(
                        'edit' => array(
                            'title' => 'Редактировать',
                            'descr' => 'Меню',
                            'uri' => $request->getBasePath() . '/',
                            'default' => true,
                        ),
                        'add' => array(
                            'title' => 'Добавить пункт меню',
                            'uri' => $request->getBasePath() . '/',
                        ),
                        'cmf_node_properties' => array(
                            'title' => 'Свойства ноды',
                            'uri' => $request->getBaseUrl() . '/',
                        ),
                    ),
                ),
            );
            */

            $cmf_front_controls = [
                'toolbar' => $this->get('engine.toolbar')->getArray(),
                'node' => $this->cmf_front_controls['node'],
            ];

            $this->get('engine.jslib')->request('bootstrap');
            $this->get('engine.jslib')->request('jquery-cookie');
            $this->get('html')
                ->css($this->get('engine.context')->getGlobalAssets() . 'cmf/frontend.css')
                ->js($this->get('engine.context')->getGlobalAssets() . 'cmf/frontend.js')
                ->js($this->get('engine.context')->getGlobalAssets() . 'cmf/jquery.ba-hashchange.min.js')
                ->appendToHead('<script type="text/javascript">var cmf_front_controls = ' . json_encode($cmf_front_controls, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) . ';</script>');
            ;
        }

        // @todo подумать как задавать темы оформления и убрать отсюда.
        $theme_path = $this->get('engine.context')->getThemePath();
        $this->View->assets = [
            'theme_path'     => $theme_path,
            'theme_css_path' => $theme_path . 'css/',
            'theme_js_path'  => $theme_path . 'js/',
            'theme_img_path' => $theme_path . 'images/',
            'vendor'         => $this->get('engine.context')->getGlobalAssets(),
        ];

        $this->get('engine.theme')->processConfig($this->View);

        foreach ($this->get('engine.jslib')->all() as $res) {
            if (isset($res['js']) and is_array($res['js'])) {
                foreach ($res['js'] as $js) {
                    $this->get('html')->js($js, 200);
                }
            }
            if (isset($res['css']) and is_array($res['css'])) {
                foreach ($res['css'] as $css) {
                    $this->get('html')->css($css, 200);
                }
            }
        }
    }
    
    /**
     * Сборка "блоков" из подготовленного списка нод.
     * По мере прохождения, подключаются и запускаются нужные модули с нужными параметрами.
     *
     * @param array $nodes_list
     */
    protected function buildModulesData(array $nodes_list)
    {
        define('_IS_CACHE_NODES', false); // @todo remove

        /** @var $node \SmartCore\Bundle\EngineBundle\Entity\Node */
        foreach ($nodes_list as $node_id => $node) {
            $block_name = $node->getBlock()->getName();

            if (!$this->View->blocks->has($block_name)) {
                $this->View->blocks->set($block_name, new View());
            }

            // Обнаружены параметры кеша.
            if (_IS_CACHE_NODES and $node['is_cached'] and !empty($node['cache_params']) ) { // and $this->get('engine.context')->cache_enable
                $cache_params = unserialize($node['cache_params']);
                if (isset($cache_params['id']) and is_array($cache_params['id'])) {
                    $cache_id = [];
                    foreach ($cache_params['id'] as $key => $dummy) {
                        switch ($key) {
                            case 'current_folder_id':
                                $cache_id['current_folder_id'] = $this->get('engine.context')->getCurrentFolderId();
                                break;
                            case 'user_id':
                                $cache_id['user_id'] = $this->getUser();
                                break;
                            case 'parser_data': // @todo route_data
                                $cache_id['parser_data'] = $node_properties['parser_data'];
                                break;
                            case 'request_uri':
                                $cache_id['parser_data'] = $_SERVER['REQUEST_URI'];
                                break;
                            case 'user_groups':
                                $user_data = $this->User->getData(); // @todo
                                $cache_id['user_groups'] = $user_data['groups'];
                                break;
                            default;
                        }
                    }
                    $cache_params['id'] = $cache_id;
                }
                $cache_params['id']['node_id'] = $node_id;
                $cache_params['nodes'][$node_id] = 1;
            } else {
                $cache_params = null;
            }

            // Попытка взять HTML кеш ноды.
            if (_IS_CACHE_NODES
                and !empty($cache_params)
                and $this->Cookie->sc_frontend_mode !== 'edit'
                and $html_cache = $this->Cache_Node->loadHtml($cache_params['id'])
            ) {
                // $this->EE->data[$block_name][$node_id]['html_cache'] = $html_cache; @todo !!!!!!!!
            } else {
                // Кеша нет.

                // Если разрешены права на запись ноды, то создаётся объект с административными методами и запрашивается у него данные для фронтальных элементов управления.
                /*
                if ($this->Permissions->isAllowed('node', 'write', $node_properties['permissions']) and ($this->Permissions->isRoot() or $this->Permissions->isAdmin()) ) {
                    $Module = $Node->getModuleInstance($node_id, true);
                } else {
                    $Module = $Node->getModuleInstance($node_id, false);
                } */

                // Выполняется модуль, все параметры ноды берутся в SmartCore\Bundle\EngineBundle\Listener\ModuleControllerModifierListener
                \Profiler::start($node_id . ' ' . $node->getModule(), 'node');
                $Module = $this->forward($node_id, [ '_eip' => true ]);
                \Profiler::end($node_id . ' ' . $node->getModule(), 'node');

                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    if (method_exists($Module, 'getFrontControls')) {
                        $this->cmf_front_controls['node']['__node_' . $node_id] = $Module->getFrontControls();
                    }

                    $this->cmf_front_controls['node']['__node_' . $node_id]['cmf_node_properties'] = [
                        'title' => 'Свойства ноды',
                        'uri'   => $this->generateUrl('cmf_admin_structure_node_properties', ['id' => $node_id])
                    ];
                }

                // Указать шаблонизатору, что надо сохранить эту ноду как html.
                // @todo ПЕРЕДЕЛАТЬ!!! подумать где выполнять кеширование, внутри объекта View или где-то снаружи.
                // @todo ВАЖНО подумать как тут поступить т.к. эта кука может стоять у гостя!!!
                //if (_IS_CACHE_NODES and !empty($cache_params) and $this->Cookie->sc_frontend_mode !== 'edit') {
                //    $this->EE->data[$block_name][$node_id]['store_html_cache'] = $Module->getCacheParams($cache_params);
                //}

                // Получение данных для фронт-админки ноды.
                // @todo сделать нормальную проверку на возможность управления нодой. сейчас пока считается, что юзер с ИД = 1 имеет право админить.
                // @todo также тут надо учитывать режим Фронт-Админки. если он выключен, то вытягивать фронт-контролсы нет смысла.
                
                //if ($this->Permissions->isAllowed('node', 'write', $node_properties['permissions']) and $this->Cookie->sc_frontend_mode == 'edit') {
                /*
                if ( false ) {
                    $front_controls = $Module->getFrontControls();
                    
                    // Для рута добавляется пунктик "свойства ноды"
                    if ($this->Permissions->isRoot()) {
                        $front_controls['_node_properties'] = [
                            'popup_window_title' => 'Свойства ноды' . " ( $node_id )",
                            'title'              => 'Свойства',
                            'link'               => HTTP_ROOT . ADMIN . '/structure/node/' . $node_id . '/?popup',
                            'ico'                => 'edit',
                        ];
                    }

                    if(is_array($front_controls)) {
                        // @todo сделать выбор типа фронт админки popup/built-in/ajax.
                        $this->View->admin['frontend'][$node_id] = [
                            // 'type' => 'popup',
                            'node_action_mode'  => $node_properties['node_action_mode'],
                            'doubleclick'       => '@todo двойной щелчок по блоку НОДЫ.',
                            'default_action'    => $Module->getFrontControlsDefaultAction(),
                            // элементы управления, относящиеся ко всей ноде.
                            'controls'          => $front_controls,
                            // элементы управления блоков внутри ноды.
                            //'controls_inner_default_action' = $Module->getFrontControlsInnerDefaultAction(),
                            'controls_inner'    => $Module->getFrontControlsInner(),
                        ];
                    }

                    $Module->View->setDecorators("<div class=\"cmf-frontadmin-node\" id=\"_node$node_id\">", "</div>");
                }
                */
            }

            $this->View->blocks->$block_name->$node_id = method_exists($Module, 'getContentRaw')
                ? $Module->getContentRaw()
                : $Module->getContent();

            // @todo пока так выставляются декораторы обрамления ноды.
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')
                and !$this->get('request')->isXmlHttpRequest()
                and $this->View->blocks->$block_name->$node_id instanceof View
            ) {
                $this->View->blocks->$block_name->$node_id->setDecorators("<div class=\"cmf-frontadmin-node\" id=\"__node_{$node_id}\">", "</div>");
            }

            unset($Module);
        }
    }

    /**
     * Обработчик POST запросов.
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse|RedirectResponse|Response
     */
    public function postAction(Request $request, $slug)
    {
        // @todo убрать - это был хак для оверлеев.
        if ($request->request->get('submit') === 'cancel') {
            return new RedirectResponse($request->server->get('HTTP_REFERER') . '#');
        }

        // Получение $node_id
        $data = $request->request->all();
        $node_id = null;
        foreach ($data as $key => $value) {
            if ($key == '_node_id') {
                $node_id = $data['_node_id'];
                unset($data['_node_id']);
                break;
            }

            if (array_key_exists('_node_id', $value)) {
                $node_id = $data[$key]['_node_id'];
                unset($data[$key]['_node_id']);
                break;
            }
        }
        foreach ($data as $key => $value) {
            $request->request->set($key, $value);
        }

        // @todo УБРАТЬ, это сейчас тут тесты с регистрацией...
        if (isset($_POST['fos_user_registration_form']) or
            isset($_POST['fos_user_profile_form']) or
            isset($_POST['fos_user_resetting_form']) or
            isset($_POST['fos_user_change_password_form']) or
            $request->getBaseUrl() . '/' . $slug === $this->container->get('router')->generate('fos_user_resetting_send_email') or
            $request->getBaseUrl() . '/' . $slug === $this->container->get('router')->generate('fos_user_resetting_check_email')
        ) {
            return $this->runAction($request, $slug);
        }

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return new JsonResponse([
                'status'  => 'INVALID',
                'message' => 'Access denied',
            ], 403);
        }

        $module_name = $this->get('engine.node')->get($node_id)->getModule();

        return $this->forward("{$node_id}:{$module_name}:post");
    }
}
