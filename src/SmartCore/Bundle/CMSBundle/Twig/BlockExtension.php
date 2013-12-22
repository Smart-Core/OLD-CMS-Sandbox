<?php
namespace SmartCore\Bundle\CMSBundle\Twig;

class BlockExtension extends \Twig_Extension
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
            'cms_block' => new \Twig_Function_Method($this, 'render'),
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
        if (empty($this->view)) {
            throw new \Exception(123);
        }

        return null;
        //return "<h1>@todo $name</h1>";
    }

    public function getName()
    {
        return 'smart_core_twig_extension';
    }
}
