<?php

namespace SmartCore\Module\Widget\Controller;

use SmartCore\Bundle\CMSBundle\Module\CacheTrait;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class WidgetController extends Controller
{
    use CacheTrait;
    use NodeTrait;

    /** @var int */
    protected $node_id;

    /**
     * В формате: "ExampleWidget:getLast".
     *
     * @var string
     */
    protected $controller;

    /**
     * В формате YAML.
     *
     * @var string
     */
    protected $params;

    /** @var string */
    protected $tamplate_theme = null;

    /** @var string */
    protected $open_tag = null;

    /** @var string */
    protected $close_tag = null;

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (null === $node = $this->get('cms.node')->get($this->node_id)) {
            return new Response('Node doen\'t exist.');
        }

        if ($node->isDeleted() or $node->isNotActive()) {
            return new Response('Node is not active.');
        }

        $cacheKey = md5('smart_module.widget.yaml_params'.$this->node_id.$this->controller.$this->params);
        if (false === $path = $this->getCacheService()->get($cacheKey)) {
            $path = Yaml::parse($this->params);
            $path['_controller'] = $this->node_id.':'.$this->controller;
            $path['_node'] = $node;
            $path['_route_params']['slug'] = substr(str_replace($request->getBaseUrl(), '', $this->get('cms.router')->getPath($node)), 1);

            $this->getCacheService()->set($cacheKey, $path, ['smart_module.widget', 'folder', 'node_'.$this->node_id, 'node']);
        }

        if ($this->get('cms.module')->has($path['_node']->getModule()) and !empty($this->controller)) {
            $originalTpl = $node->getTemplate();
            $node->setTemplate($this->tamplate_theme);
            $response = $this->forward($this->node_id.':'.$this->controller, $path);
            $node->setTemplate($originalTpl);
        } else {
            return new Response('Module "'.$path['_node']->getModule().'" is unavailable.');
        }

        if ($response->isServerError()) {
            //return new Response($response->getStatusCode() . ' ' . Response::$statusTexts[$response->getStatusCode()]);
            return $response; // @todo FS#402
        }

        if (strlen(trim($response->getContent())) > 0) {
            $response->setContent(
                $this->open_tag."\n".
                $response->getContent()."\n".
                $this->close_tag
            );
        }

        return $response;
    }
}
