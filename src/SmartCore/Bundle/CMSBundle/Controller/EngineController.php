<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use SmartCore\Bundle\CMSBundle\Entity\Node;
use SmartCore\Bundle\CMSBundle\Twig\RegionRenderHelper;
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
    protected $front_controls;

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

        if (empty($router_data['folders'])) { // Случай пустой инсталляции, когда еще ни одна папка не создана.
            $this->get('cms.toolbar')->prepare();

            return $this->render('CMSBundle::welcome.html.twig');
        }

        if ($router_data['status'] == 404) {
            throw new NotFoundHttpException('Page not found.');
        } elseif ($router_data['status'] == 403) {
            throw new AccessDeniedHttpException('Access Denied.');
        }

        $this->get('html')->setMetas($router_data['meta']);

        foreach ($router_data['folders'] as $folder) {
            $this->get('cms.breadcrumbs')->add($this->get('cms.folder')->getUri($folder), $folder->getTitle(), $folder->getDescr());
        }

        $this->container->get('cms.context')->setCurrentFolderId($router_data['current_folder_id']);
        $this->container->get('cms.context')->setCurrentFolderPath($router_data['current_folder_path']);

        // Список нод кешируется только при GET запросах.
        $router_data['http_method'] = $request->getMethod();

        $nodes = $this->get('cms.node')->buildList($router_data);

        \Profiler::start('buildModulesData');
        $nodesResponses = $this->buildModulesData($request, $nodes);
        \Profiler::end('buildModulesData');

        if ($nodesResponses instanceof Response) {
            return $nodesResponses;
        }

        $this->get('cms.toolbar')->prepare($this->front_controls['node']);

        return new Response($this->renderView("::{$router_data['template']}.html.twig", $nodesResponses), $router_data['status']);
    }

    /**
     * Сборка "блоков" из подготовленного списка нод.
     * По мере прохождения, подключаются и запускаются нужные модули с нужными параметрами.
     *
     * @param Request $request
     * @param \SmartCore\Bundle\CMSBundle\Entity\Node[] $nodes
     * @return array|Response|RedirectResponse
     */
    protected function buildModulesData(Request $request, array $nodes)
    {
        $prioritySorted = [];
        $nodesResponses = [];

        foreach ($nodes as $node) {
            if (!isset($nodesResponses[$node->getRegionName()])) {
                $nodesResponses[$node->getRegionName()] = new RegionRenderHelper();
            }

            $prioritySorted[$node->getPriority()][$node->getId()] = $node;
            $nodesResponses[$node->getRegionName()]->{$node->getId()} = new Response();
        }

        krsort($prioritySorted);

        foreach ($prioritySorted as $nodes) {
            /** @var \SmartCore\Bundle\CMSBundle\Entity\Node $node */
            foreach ($nodes as $node) {
                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $node->setEip(true);
                }

                // Выполняется модуль, все параметры ноды берутся в \SmartCore\Bundle\CMSBundle\Listener\ModuleControllerModifierListener
                \Profiler::start($node->getId() . ' ' . $node->getModule(), 'node');

                $moduleResponse = $this->forward($node->getId(), [
                    '_route' => 'cms_node_mapper',
                    '_route_params' => $request->attributes->get('_route_params'),
                ], $request->query->all());

                \Profiler::end($node->getId() . ' ' . $node->getModule(), 'node');

                if ($moduleResponse instanceof RedirectResponse
                    or ($moduleResponse instanceof Response and $moduleResponse->isNotFound())
                    or 0 === strpos($moduleResponse->getContent(), '<!DOCTYPE ') // Пока так определяются ошибки от симфони.
                ) {
                    return $moduleResponse;
                }

                // @todo сделать отправку front_controls в ответе метода.
                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $this->front_controls['node']['__node_' . $node->getId()] = $node->getFrontControls();
                    $this->front_controls['node']['__node_' . $node->getId()]['cms_node_properties'] = [
                        'title' => 'Параметры модуля ' . $node->getModule(), // @todo translate
                        'uri'   => $this->generateUrl('cms_admin_structure_node_properties', ['id' => $node->getId()])
                    ];
                }

                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $moduleResponse->setContent(
                        "\n<div class=\"cmf-frontadmin-node\" id=\"__node_{$node->getId()}\">\n" . $moduleResponse->getContent() . "\n</div>\n"
                    );
                }

                $nodesResponses[$node->getRegionName()]->{$node->getId()} = $moduleResponse;
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

        if (!$node instanceof Node or $node->isDisabled()) {
            throw new AccessDeniedHttpException('Node is disabled.');
        }

        // @todo сделать здесь проверку на права доступа, а также доступность ноды в запрошенной папке.

        // @todo сделать роутинги для POST запросов к нодам.
        return $this->forward("{$node->getId()}:{$node->getModule()}:post", ['slug' => $slug]);
    }
}
