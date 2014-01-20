<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SmartCore\Bundle\CMSBundle\Engine\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EngineController extends Controller
{
    /**
     * Коллекция фронтальных элементов управления.
     *
     * @var array
     */
    protected $cms_front_controls;

    /**
     * @param Request $request
     * @param string $slug
     * @return Response
     */
    public function runAction(Request $request, $slug)
    {
        /** @var \RickySu\Tagcache\Adapter\TagcacheAdapter $tagcache */
        $tagcache = $this->get('tagcache');

        // Кеширование роутера.
        $cache_key = md5('cms_router' . $request->getBaseUrl() . $slug);
        if (false == $router_data = $tagcache->get($cache_key)) {
            $router_data = $this->get('cms.router')->match($request->getBaseUrl(), $slug);
            $tagcache->set($cache_key, $router_data, ['folder', 'node']);
        }

        if ($router_data['status'] == 404) {
            throw new NotFoundHttpException('Page not found.');
        } elseif ($router_data['status'] == 403) {
            throw new AccessDeniedHttpException('Access Denied.');
        }

        foreach ($router_data['folders'] as $folder) {
            $this->get('cms.breadcrumbs')->add($folder->getUri(), $folder->getTitle(), $folder->getDescr());
        }

        $this->container->get('cms.context')->setCurrentFolderId($router_data['current_folder_id']);
        $this->container->get('cms.context')->setCurrentFolderPath($router_data['current_folder_path']);

        $nodes_list = $this->get('cms.node')->buildList($router_data);

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

        // Обход всех вьюшек нод и рендеринг шаблонов модулей. Это для того, чтобы симфони мог обрабатывать ошибки в шаблонах.
        if ($this->get('kernel')->isDebug()) {
            foreach ($this->View->blocks as $block) {
                /** @var View $nodeView */
                foreach ($block as $nodeView) {
                    if ($nodeView instanceof View and $nodeView->getEngine() != 'echo') {
                        $data = $nodeView->render();
                        $nodeView->removeProperties()->setDecorators(null, null)->setEngine('echo')->set('data', $data);
                    }
                }
            }
        }

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
        // @todo убрать в ini-шник шаблона.
        $this->get('html')->meta('viewport', 'width=device-width, initial-scale=1.0');

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $cms_front_controls = [
                'toolbar' => $this->get('cms.toolbar')->getArray(),
                'node'    => $this->cms_front_controls['node'],
            ];

            $this->get('cms.jslib')->call('bootstrap');
            $this->get('cms.jslib')->call('jquery-cookie');
            $this->get('html')
                ->css($this->get('cms.context')->getGlobalAssets() . 'cmf/frontend.css')
                ->js($this->get('cms.context')->getGlobalAssets() . 'cmf/frontend.js')
                ->js($this->get('cms.context')->getGlobalAssets() . 'cmf/jquery.ba-hashchange.min.js')
                ->appendToHead('<script type="text/javascript">var cms_front_controls = ' . json_encode($cms_front_controls, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) . ';</script>');
            ;
        }

        // @todo подумать как задавать темы оформления и убрать отсюда.
        $theme_path = $this->get('cms.context')->getThemePath();
        $this->View->assets = [
            'theme_path'     => $theme_path,
            'theme_css_path' => $theme_path . 'css/',
            'theme_js_path'  => $theme_path . 'js/',
            'theme_img_path' => $theme_path . 'images/',
            'vendor'         => $this->get('cms.context')->getGlobalAssets(),
        ];

        $this->get('cms.theme')->processConfig($this->View);

        foreach ($this->get('cms.jslib')->all() as $res) {
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
        /** @var $node \SmartCore\Bundle\CMSBundle\Entity\Node */
        foreach ($nodes_list as $node_id => $node) {
            $block_name = $node->getBlock()->getName();

            if (!$this->View->blocks->has($block_name)) {
                $this->View->blocks->set($block_name, new View());
            }

            // Выполняется модуль, все параметры ноды берутся в SmartCore\Bundle\CMSBundle\Listener\ModuleControllerModifierListener
            \Profiler::start($node_id . ' ' . $node->getModule(), 'node');
            $Module = $this->forward($node_id, [ '_eip' => true ]);
            \Profiler::end($node_id . ' ' . $node->getModule(), 'node');

            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                if (method_exists($Module, 'getFrontControls')) {
                    $this->cms_front_controls['node']['__node_' . $node_id] = $Module->getFrontControls();
                }

                $this->cms_front_controls['node']['__node_' . $node_id]['cms_node_properties'] = [
                    'title' => 'Свойства ноды', // @todo translate
                    'uri'   => $this->generateUrl('cms_admin_structure_node_properties', ['id' => $node_id])
                ];
            }

            $this->View->blocks->$block_name->$node_id = method_exists($Module, 'getContentRaw')
                ? $Module->getContentRaw()
                : $Module->getContent();

            // @todo пока так выставляются декораторы обрамления ноды.
            if ($this->get('security.context')->isGranted('ROLE_ADMIN') and $this->View->blocks->$block_name->$node_id instanceof View) {
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
     * @return RedirectResponse|Response
     *
     * @todo продумать!
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

            if (is_array($value) and array_key_exists('_node_id', $value)) {
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

        $module_name = $this->get('cms.node')->get($node_id)->getModule();

        return $this->forward("{$node_id}:{$module_name}:post");
    }
}
