<?php

namespace SmartCore\Bundle\CMSBundle\Twig;

use SmartCore\Bundle\CMSBundle\Tools\Breadcrumbs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HtmlTitleExtension extends \Twig_Extension
{
    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->breadcrumbs     = $container->get('cms.breadcrumbs');

        $this->options = [
            'delimiter' => '/',
            'site_short_name' => 'MySite',
            'site_full_name'  => 'Wellcome to MySite!',
        ];
    }

    public function getFunctions()
    {
        return [
            'cms_html_title' => new \Twig_Function_Method($this, 'render'),
        ];
    }

    /**
     * @param array $options
     * @return string
     */
    public function render(array $options = [])
    {
        $options = $options + $this->options;

        $response = (count($this->breadcrumbs) <= 1) ? $options['site_full_name'] : $options['site_short_name'];

        foreach ($this->breadcrumbs->all() as $key => $value) {
            if ($key == 0) {
                continue;
            }

            $response = $value['title'] . ' ' . $options['delimiter'] . ' ' . $response;
        }

        return $response;
    }

    public function getName()
    {
        return 'smart_core_cms_html_title_twig_extension';
    }
}
