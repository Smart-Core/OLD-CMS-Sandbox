<?php

namespace SmartCore\Module\Breadcrumbs\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BreadcrumbsController extends Controller
{
    use NodeTrait;

    /**
     * Разделитель.
     *
     * @var string
     */
    protected $delimiter = '»'; //'&raquo;'; // @todo подумать как можно в форме свойств ноды, экранировать спец символы.

    /**
     * Скрыть "хлебные крошки", если выбрана корневая папка.
     *
     * @var bool
     */
    protected $hide_if_only_home = false;

    /**
     * @var string|null
     */
    protected $css_class = null;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('BreadcrumbsModule::breadcrumbs.html.php', [
            'css_class' => $this->css_class,
            'delimiter' => $this->delimiter,
            'items'     => $this->get('cms.breadcrumbs'),
            'hide_if_only_home' => $this->hide_if_only_home,
        ]);
    }
}
