<?php
namespace SmartCore\Bundle\CMSBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Router;

class CmsModulePathExtension extends \Twig_Extension
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            'cms_module_path' => new \Twig_Function_Method($this, 'render'),
            'cms_module_admin_path' => new \Twig_Function_Method($this, 'generateModuleAdminUrl'),
            'path_admin' => new \Twig_Function_Method($this, 'generateModuleAdminUrl'),
        ];
    }

    /**
     * Генерация пути к папке ноды.
     *
     * @param null $name
     * @param string $route
     * @param array $args
     * @return string
     */
    public function render($node, $route, $args = [])
    {
        switch ($route) {
            case 'smart_module_news.item': // @todo сделать роутинг модулей.
                return $this->container->get('cms.folder')->getUri($node->getFolderId()) . $args['slug'] . '.html';
                break;
            default:
                break;
        }

        return "<h6>@todo $route</h6>";
    }

    public function generateModuleAdminUrl($route, $parameters = [])
    {
        return $this->container->get('cms.router')->generateModuleAdminUrl($route, $parameters);
    }
    
    public function getName()
    {
        return 'smart_core_twig_extension';
    }
}
