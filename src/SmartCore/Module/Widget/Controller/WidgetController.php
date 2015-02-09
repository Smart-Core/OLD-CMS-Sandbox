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

    /**
     * @var int
     */
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

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (null === $node = $this->get('cms.node')->get($this->node_id)) {
            return new Response('Node doen\'t exist.');
        }

        $cacheKey = md5('smart_module.widget.yaml_params'.$this->node_id.$this->controller.$this->params);
        if (false === $path = $this->getCacheService()->get($cacheKey)) {
            $path = Yaml::parse($this->params);
            $path['_controller'] = $this->node_id.':'.$this->controller;
            $path['_node'] = $node;
            $path['_route_params']['slug'] = substr(str_replace($request->getBaseUrl(), '', $this->get('cms.router')->getPath($node)), 1);

            $this->getCacheService()->set($cacheKey, $path, ['smart_module.widget', 'folder', 'node_'.$this->node_id, 'node']);
        }

        $response = $this->forward($this->node_id.':'.$this->controller, $path);

        if ($response->isServerError()) {
            //return new Response($response->getStatusCode() . ' ' . Response::$statusTexts[$response->getStatusCode()]);
            return $response; // @todo FS#402
        }

        if (strlen(trim($response->getContent())) > 0) {
            $response->setContent(
                $this->node->getParam('open_tag')."\n".
                $response->getContent()."\n".
                $this->node->getParam('close_tag')
            );
        }

        return $response;
    }
}
