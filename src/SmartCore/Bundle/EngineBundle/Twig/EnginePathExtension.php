<?php
namespace SmartCore\Bundle\EngineBundle\Twig;

class EnginePathExtension extends \Twig_Extension
{
    protected $view = null;

    /**
     * Constructor.
     */
    public function __construct($container = null)
    {
        $this->view = true;
    }

    public function getFunctions()
    {
        return [
            'engine_path' => new \Twig_Function_Method($this, 'render'),
        ];
    }

    /**
     * Render block.
     *
     * @param string $string
     * @return string
     */
    public function render($name = null)
    {
        return "<h1>@todo $name</h1>";
    }

    public function getName()
    {
        return 'smart_core_twig_extension';
    }
}
