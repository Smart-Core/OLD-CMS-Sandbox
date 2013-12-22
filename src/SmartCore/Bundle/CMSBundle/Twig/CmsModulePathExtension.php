<?php
namespace SmartCore\Bundle\CMSBundle\Twig;

class CmsModulePathExtension extends \Twig_Extension
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            'cms_module_path' => new \Twig_Function_Method($this, 'render'),
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

    public function getName()
    {
        return 'smart_core_twig_extension';
    }
}
