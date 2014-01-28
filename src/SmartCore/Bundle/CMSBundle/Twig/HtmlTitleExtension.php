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
        $this->breadcrumbs = $container->get('cms.breadcrumbs');

        $this->options = [
            'delimiter'       => '/',
            'site_short_name' => 'MySite',
            'site_full_name'  => 'Wellcome to MySite!',
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            'cms_html_title' => new \Twig_Function_Method($this, 'renderTitle'),
        ];
    }

    /**
     * @param  array $options
     * @return string
     */
    public function renderTitle(array $options = [])
    {
        $options = $options + $this->options;

        $response = (count($this->breadcrumbs) <= 1)
            ? $options['site_full_name']
            : $options['site_short_name'];

        foreach ($this->breadcrumbs->all() as $key => $value) {
            if ($key == 0) {
                continue;
            }

            $response = $value['title'] . ' ' . $options['delimiter'] . ' ' . $response;
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smart_core_cms_html_title_twig_extension';
    }
}
