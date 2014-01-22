<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use SmartCore\Bundle\CMSBundle\Twig\BlockRenderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        $router_data['http_method'] = $request->getMethod(); // @fixme это эксперименты с кешированием списка нод.

        $nodes = $this->get('cms.node')->buildList($router_data);

        \Profiler::start('buildModulesData');
        $nodesResponses = $this->buildModulesData($nodes);
        \Profiler::end('buildModulesData');

        if ($nodesResponses instanceof RedirectResponse) {
            return $nodesResponses;
        }

        $this->buildBaseHtml($router_data['template']);

        return new Response($this->renderView("::{$router_data['template']}.html.twig", $nodesResponses), $router_data['status']);
    }

    /**
     * Временный метод...
     *
     * @todo отрефакторить!!!
     */
    protected function buildBaseHtml($template)
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
        $assets = [
            'theme_path'     => $theme_path,
            'theme_css_path' => $theme_path . 'css/',
            'theme_js_path'  => $theme_path . 'js/',
            'theme_img_path' => $theme_path . 'images/',
            'vendor'         => $this->get('cms.context')->getGlobalAssets(),
        ];

        $this->get('cms.theme')->processConfig($assets, $template);

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
     * @param \SmartCore\Bundle\CMSBundle\Entity\Node[] $nodes_list
     * @return array|RedirectResponse
     */
    protected function buildModulesData(array $nodes)
    {
        $prioritySorted = [];
        $nodesResponses = [];

        /** @var \SmartCore\Bundle\CMSBundle\Entity\Node $node */
        foreach ($nodes as $node) {
            if (!isset($nodesResponses[$node->getBlockName()])) {
                $nodesResponses[$node->getBlockName()] = new BlockRenderHelper();
            }

            $prioritySorted[$node->getPriority()][$node->getId()] = $node;
            $nodesResponses[$node->getBlockName()]->{$node->getId()} = new Response();
        }

        krsort($prioritySorted);

        foreach ($prioritySorted as $nodes) {
            foreach ($nodes as $node) {
                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $node->setEip(true);
                }

                // Выполняется модуль, все параметры ноды берутся в \SmartCore\Bundle\CMSBundle\Listener\ModuleControllerModifierListener
                \Profiler::start($node->getId() . ' ' . $node->getModule(), 'node');
                $moduleResponse = $this->forward($node->getId());
                \Profiler::end($node->getId() . ' ' . $node->getModule(), 'node');

                if ($moduleResponse instanceof RedirectResponse) {
                    return $moduleResponse;
                }

                // @todo сделать отправку front_controls в ответе.
                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $this->cms_front_controls['node']['__node_' . $node->getId()] = $node->getFrontControls();
                    $this->cms_front_controls['node']['__node_' . $node->getId()]['cms_node_properties'] = [
                        'title' => 'Свойства ноды', // @todo translate
                        'uri'   => $this->generateUrl('cms_admin_structure_node_properties', ['id' => $node->getId()])
                    ];
                }

                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $moduleResponse->setContent(
                        "<div class=\"cmf-frontadmin-node\" id=\"__node_{$node->getId()}\">" . $moduleResponse->getContent() . "</div>"
                    );
                }

                $nodesResponses[$node->getBlockName()]->{$node->getId()} = $moduleResponse;
            }
        }

        return $nodesResponses;
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

        $node = $this->get('cms.node')->get($node_id);

        if ($node->isDisabled()) {
            throw new AccessDeniedHttpException('Node is disabled.');
        }

        // @todo сделать здесь проверку на права доступа, а также доступность ноды в запрошенной папке.

        // @todo сделать роутинги для POST запросов к нодам.
        return $this->forward("{$node->getId()}:{$node->getModule()}:post", ['slug' => $slug]);
    }
}
